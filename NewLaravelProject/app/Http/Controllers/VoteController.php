<?php

namespace App\Http\Controllers;

use App\Models\Festivity;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VoteController extends Controller
{
    public function store(Request $request, Festivity $festivity)
    {
        $user = Auth::user();
        
        try {
            // Verificar si el usuario ya votó por cualquier festividad hoy
            $existingVote = Vote::where('user_id', $user->id)
                ->where('voted_at', now()->toDateString())
                ->exists();
                
            if ($existingVote) {
                return back()->with('error', 'Ya has votado por una festividad hoy. Solo puedes votar una vez al día.');
            }
            
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
            // Si hay un error de restricción única, significa que ya votó hoy
            if ($e->getCode() == 23000) { // SQLITE_CONSTRAINT_UNIQUE
                return back()->with('error', 'Ya has votado por una festividad hoy. Solo puedes votar una vez al día.');
            }
            
            // Otros errores de base de datos
            return back()->with('error', 'Ha ocurrido un error al procesar tu voto. Por favor, inténtalo de nuevo.');
        }
    }

    public function mostVoted()
    {
        $mostVotedFestivities = Festivity::withCount('votes')
            ->orderBy('votes_count', 'desc')
            ->limit(7)
            ->get();
        
        // Verificar si el usuario ya votó hoy
        $userVotedToday = false;
        if (Auth::check()) {
            $userVotedToday = Vote::where('user_id', Auth::id())
                ->where('voted_at', now()->toDateString())
                ->exists();
        }
            
        return view('festivities.most-voted', compact('mostVotedFestivities', 'userVotedToday'));
    }

    public static function userVotedToday()
    {
        if (!Auth::check()) {
            return false;
        }
        
        return Vote::where('user_id', Auth::id())
            ->where('voted_at', now()->toDateString())
            ->exists();
    }
}
