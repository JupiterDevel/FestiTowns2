<?php

namespace App\Http\Controllers;

use App\Models\Locality;
use App\Models\Festivity;
use App\Services\SearchService;
use App\Services\SeoService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function __construct(
        private SearchService $searchService
    ) {
    }

    /**
     * Realiza búsqueda inteligente en localidades
     */
    private function searchLocalities($query)
    {
        $expandedQueries = $this->searchService->expandSearchQuery($query);
        
        // Obtener todas las localidades y filtrar en PHP para evitar consultas complejas
        $allLocalities = Locality::withCount('festivities')->get();
        
        $filteredLocalities = $allLocalities->filter(function ($locality) use ($expandedQueries) {
            // Check name
            if ($this->searchService->matchesExpandedQuery($locality->name, $expandedQueries)) {
                return true;
            }
            
            // Check description
            if ($locality->description && $this->searchService->matchesExpandedQuery($locality->description, $expandedQueries)) {
                return true;
            }
            
            // Check address
            if ($locality->address && $this->searchService->matchesExpandedQuery($locality->address, $expandedQueries)) {
                return true;
            }
            
            return false;
        });
        
        // Ordenar por relevancia
        $sortedLocalities = $filteredLocalities->sort(function ($a, $b) use ($query) {
            $aName = $this->searchService->normalizeText($a->name);
            $bName = $this->searchService->normalizeText($b->name);
            $queryLower = $this->searchService->normalizeText($query);
            
            // Prioridad: exacta > empieza con > contiene
            $aScore = $this->searchService->calculateRelevanceScore($aName, $queryLower);
            $bScore = $this->searchService->calculateRelevanceScore($bName, $queryLower);
            
            if ($aScore === $bScore) {
                return strcmp($a->name, $b->name);
            }
            
            return $aScore - $bScore;
        });
        
        // Convertir a paginación manual
        $page = request()->get('page', 1);
        $perPage = 10;
        $offset = ($page - 1) * $perPage;
        
        $paginatedItems = $sortedLocalities->slice($offset, $perPage)->values();
        
        return new \Illuminate\Pagination\LengthAwarePaginator(
            $paginatedItems,
            $sortedLocalities->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'pageName' => 'page']
        );
    }

    /**
     * Realiza búsqueda inteligente en festividades
     */
    private function searchFestivities($query)
    {
        $expandedQueries = $this->searchService->expandSearchQuery($query);
        
        // Obtener todas las festividades y filtrar en PHP para evitar consultas complejas
        $allFestivities = Festivity::with(['locality', 'votes'])->get();
        
        $filteredFestivities = $allFestivities->filter(function ($festivity) use ($expandedQueries) {
            // Check festivity name
            if ($this->searchService->matchesExpandedQuery($festivity->name, $expandedQueries)) {
                return true;
            }
            
            // Check description
            if ($festivity->description && $this->searchService->matchesExpandedQuery($festivity->description, $expandedQueries)) {
                return true;
            }
            
            // Check locality name
            if ($festivity->locality && $this->searchService->matchesExpandedQuery($festivity->locality->name, $expandedQueries)) {
                return true;
            }
            
            return false;
        });
        
        // Ordenar por relevancia
        $sortedFestivities = $filteredFestivities->sort(function ($a, $b) use ($query) {
            $aName = $this->searchService->normalizeText($a->name);
            $bName = $this->searchService->normalizeText($b->name);
            $queryLower = $this->searchService->normalizeText($query);
            
            $aScore = $this->searchService->calculateRelevanceScore($aName, $queryLower);
            $bScore = $this->searchService->calculateRelevanceScore($bName, $queryLower);
            
            if ($aScore === $bScore) {
                return strcmp($a->name, $b->name);
            }
            
            return $aScore - $bScore;
        });
        
        // Convertir a paginación manual
        $page = request()->get('page', 1);
        $perPage = 10;
        $offset = ($page - 1) * $perPage;
        
        $paginatedItems = $sortedFestivities->slice($offset, $perPage)->values();
        
        return new \Illuminate\Pagination\LengthAwarePaginator(
            $paginatedItems,
            $sortedFestivities->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'pageName' => 'page']
        );
    }

    /**
     * Realiza búsqueda por provincia
     */
    private function searchByProvince($province)
    {
        // Buscar festividades que pertenezcan a localidades de la provincia especificada
        // o que tengan directamente asignada esa provincia
        $festivities = Festivity::with(['locality', 'votes'])
            ->where(function ($query) use ($province) {
                $query->where('province', $province)
                      ->orWhereHas('locality', function ($localityQuery) use ($province) {
                          $localityQuery->where('province', $province);
                      });
            })
            ->orderBy('start_date')
            ->paginate(10);

        return $festivities;
    }

    public function index(Request $request)
    {
        $localities = Locality::with('festivities')->get();
        $upcomingFestivities = Festivity::with('locality')
            ->where('start_date', '>=', now())
            ->orderBy('start_date')
            ->limit(6)
            ->get();
        
        // Manejar búsquedas
        $searchResults = null;
        $searchType = $request->get('search_type', 'festivity');
        $searchQuery = $request->get('search');
        $searchDate = $request->get('search_date');
        $searchProvince = $request->get('search_province');
        
        // Determinar el tipo de búsqueda y el query correcto
        if ($searchDate && $searchType === 'date') {
            // Búsqueda por fecha
            $searchQuery = $searchDate;
        } elseif ($searchProvince && $searchType === 'province') {
            // Búsqueda por provincia
            $searchQuery = $searchProvince;
        } elseif ($searchQuery && $searchType !== 'date' && $searchType !== 'province') {
            // Búsqueda por texto (festividad o localidad)
            // No hacer nada, usar el searchQuery tal como está
        } else {
            // Si no hay parámetros válidos, limpiar todo
            $searchQuery = null;
            $searchDate = null;
            $searchProvince = null;
        }
        
        if ($searchQuery) {
            switch ($searchType) {
                case 'locality':
                    $searchResults = $this->searchLocalities($searchQuery);
                    break;
                    
                case 'festivity':
                    $searchResults = $this->searchFestivities($searchQuery);
                    break;
                    
                case 'date':
                    try {
                        $date = Carbon::parse($searchQuery);
                        $endDate = $date->copy()->addWeek();
                        
                        $searchResults = Festivity::with(['locality', 'votes'])
                            ->whereBetween('start_date', [$date->toDateString(), $endDate->toDateString()])
                            ->orderBy('start_date')
                            ->paginate(10);
                    } catch (\Exception $e) {
                        $searchResults = collect()->paginate(10);
                    }
                    break;
                    
                case 'province':
                    $searchResults = $this->searchByProvince($searchQuery);
                    break;
            }
        }
        
        // SEO Meta Tags
        $meta = SeoService::generateMetaTags([
            'title' => 'ElAlmaDeLasFiestas - Festividades y Eventos Tradicionales de España',
            'description' => 'Descubre las mejores festividades y eventos tradicionales de España. Fallas de Valencia, San Fermín, Feria de Abril y muchas más. Información sobre horarios, eventos y tradiciones.',
            'keywords' => 'festividades españa, eventos tradicionales, fiestas populares, turismo cultural, fallas valencia, san fermin, feria abril',
            'url' => route('home'),
        ]);
        
        return view('home', compact('localities', 'upcomingFestivities', 'searchResults', 'searchType', 'searchQuery', 'searchDate', 'searchProvince', 'meta'));
    }
}
