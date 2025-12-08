<?php

namespace App\Http\Controllers;

use App\Models\Festivity;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class VoteController extends Controller
{
    public function store(Request $request, Festivity $festivity)
    {
        $user = Auth::user();
        
        // Validar que el usuario esté autenticado
        if (!$user) {
            return back()->with('error', 'Debes estar autenticado para votar.');
        }
        
        // Verificar si las votaciones están habilitadas
        $votingEnabled = Cache::get('voting_enabled', true); // Por defecto habilitado
        if (!$votingEnabled) {
            return back()->with('error', 'Las votaciones están deshabilitadas temporalmente. Por favor, inténtalo más tarde.');
        }
        
        try {
            // Los administradores pueden votar múltiples veces al día
            // Los usuarios Visitor y TownHall solo pueden votar una vez al día
            $isVisitor = $user->isVisitor();
            $isTownHall = $user->isTownHall();
            $isAdmin = $user->isAdmin();
            
            // Aplicar restricción solo a Visitor y TownHall
            if ($isVisitor || $isTownHall) {
                $today = now()->toDateString();
                $existingVote = Vote::where('user_id', $user->id)
                    ->whereDate('voted_at', $today)
                    ->exists();
                    
                if ($existingVote) {
                    return back()->with('error', 'Ya has votado por una festividad hoy. Solo puedes votar una vez al día.');
                }
            }
            // Los administradores pueden votar sin restricción
            
            // Crear el voto
            Vote::create([
                'user_id' => $user->id,
                'festivity_id' => $festivity->id,
                'voted_at' => now()->toDateString(),
            ]);
            
            // Otorgar puntos por votar (solo a visitantes)
            if ($user->isVisitor()) {
                $user->addPoints(10); // 10 puntos por votar
            }
            
            return back()->with('success', '¡Voto registrado exitosamente!');
            
        } catch (\Illuminate\Database\QueryException $e) {
            // Si hay un error de restricción única (por si acaso aún existe)
            if ($e->getCode() == 23000) { // SQLITE_CONSTRAINT_UNIQUE
                // Solo aplicar restricción si no es administrador
                if ($user->isVisitor() || $user->isTownHall()) {
                    return back()->with('error', 'Ya has votado por una festividad hoy. Solo puedes votar una vez al día.');
                }
            }
            
            // Otros errores de base de datos
            return back()->with('error', 'Ha ocurrido un error al procesar tu voto. Por favor, inténtalo de nuevo.');
        }
    }

    public function mostVoted(Request $request)
    {
        $provinceMapping = config('autonomous_communities.province_to_community');
        $communities = config('autonomous_communities.communities');
        $provinces = config('provinces.provinces');
        
        // Sección Nacional: Top 7 de todas las festividades
        $nationalFestivities = Festivity::with('locality')
            ->withCount('votes')
            ->orderBy('votes_count', 'desc')
            ->limit(7)
            ->get();
        
        // Sección Regional: Ranking según comunidad autónoma
        $selectedCommunity = $request->input('community', '');
        $regionalFestivities = collect();
        
        if ($selectedCommunity) {
            // Obtener todas las provincias de la comunidad autónoma seleccionada
            $provincesInCommunity = array_keys(
                array_filter($provinceMapping, function($community) use ($selectedCommunity) {
                    return $community === $selectedCommunity;
                })
            );
            
            if (!empty($provincesInCommunity)) {
                $regionalFestivities = Festivity::with('locality')
                    ->withCount('votes')
                    ->where(function($query) use ($provincesInCommunity) {
                        $query->whereIn('province', $provincesInCommunity)
                              ->orWhereHas('locality', function($localityQuery) use ($provincesInCommunity) {
                                  $localityQuery->whereIn('province', $provincesInCommunity);
                              });
                    })
                    ->orderBy('votes_count', 'desc')
                    ->limit(7)
                    ->get();
            }
        }
        
        // Sección Provincial: Ranking según provincia
        $selectedProvince = $request->input('province', '');
        $provincialFestivities = collect();
        
        if ($selectedProvince && in_array($selectedProvince, $provinces)) {
            $provincialFestivities = Festivity::with('locality')
                ->withCount('votes')
                ->where(function($query) use ($selectedProvince) {
                    $query->where('province', $selectedProvince)
                          ->orWhereHas('locality', function($localityQuery) use ($selectedProvince) {
                              $localityQuery->where('province', $selectedProvince);
                          });
                })
                ->orderBy('votes_count', 'desc')
                ->limit(7)
                ->get();
        }
        
        // Verificar si el usuario ya votó hoy (los administradores siempre pueden votar)
        $userVotedToday = false;
        if (Auth::check()) {
            $user = Auth::user();
            // Los administradores pueden votar múltiples veces, así que siempre pueden votar
            // Visitor y TownHall solo pueden votar una vez al día
            if ($user->isVisitor() || $user->isTownHall()) {
                $userVotedToday = Vote::where('user_id', Auth::id())
                    ->whereDate('voted_at', now()->toDateString())
                    ->exists();
            }
        }
        
        // Obtener el mensaje informativo desde la caché
        $votingInfoMessage = Cache::get('voting_info_message', '');
            
        return view('festivities.most-voted', compact(
            'nationalFestivities',
            'regionalFestivities',
            'provincialFestivities',
            'selectedCommunity',
            'selectedProvince',
            'communities',
            'provinces',
            'userVotedToday',
            'votingInfoMessage'
        ));
    }

    public static function userVotedToday()
    {
        if (!Auth::check()) {
            return false;
        }
        
        $user = Auth::user();
        
        // Los administradores pueden votar múltiples veces, así que siempre pueden votar
        // Visitor y TownHall solo pueden votar una vez al día
        if ($user->isAdmin()) {
            return false; // Los admin siempre pueden votar
        }
        
        // Para Visitor y TownHall, verificar si ya votaron hoy
        return Vote::where('user_id', Auth::id())
            ->whereDate('voted_at', now()->toDateString())
            ->exists();
    }
}
