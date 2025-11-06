<?php

namespace App\Http\Controllers;

use App\Models\Locality;
use App\Models\Festivity;
use App\Services\SeoService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Normaliza el texto para búsquedas insensibles a acentos y caracteres especiales
     */
    private function normalizeText($text)
    {
        // Convertir a minúsculas
        $text = mb_strtolower($text, 'UTF-8');
        
        // Reemplazar acentos y caracteres especiales
        $replacements = [
            'á' => 'a', 'à' => 'a', 'ä' => 'a', 'â' => 'a', 'ã' => 'a',
            'é' => 'e', 'è' => 'e', 'ë' => 'e', 'ê' => 'e',
            'í' => 'i', 'ì' => 'i', 'ï' => 'i', 'î' => 'i',
            'ó' => 'o', 'ò' => 'o', 'ö' => 'o', 'ô' => 'o', 'õ' => 'o',
            'ú' => 'u', 'ù' => 'u', 'ü' => 'u', 'û' => 'u',
            'ñ' => 'n', 'ç' => 'c',
            'Á' => 'a', 'À' => 'a', 'Ä' => 'a', 'Â' => 'a', 'Ã' => 'a',
            'É' => 'e', 'È' => 'e', 'Ë' => 'e', 'Ê' => 'e',
            'Í' => 'i', 'Ì' => 'i', 'Ï' => 'i', 'Î' => 'i',
            'Ó' => 'o', 'Ò' => 'o', 'Ö' => 'o', 'Ô' => 'o', 'Õ' => 'o',
            'Ú' => 'u', 'Ù' => 'u', 'Ü' => 'u', 'Û' => 'u',
            'Ñ' => 'n', 'Ç' => 'c'
        ];
        
        return strtr($text, $replacements);
    }

    /**
     * Expande la consulta de búsqueda con sinónimos y variaciones comunes
     */
    private function expandSearchQuery($query)
    {
        $normalizedQuery = $this->normalizeText($query);
        
        // Sinónimos y variaciones comunes para festividades
        $synonyms = [
            'fiesta' => ['festividad', 'celebracion', 'evento', 'festival'],
            'festividad' => ['fiesta', 'celebracion', 'evento', 'festival'],
            'celebracion' => ['fiesta', 'festividad', 'evento', 'festival'],
            'evento' => ['fiesta', 'festividad', 'celebracion', 'festival'],
            'festival' => ['fiesta', 'festividad', 'celebracion', 'evento'],
            'feria' => ['mercado', 'exposicion', 'muestra'],
            'carnaval' => ['carnavales', 'mascarada'],
            'navidad' => ['navideño', 'navideña'],
            'semana santa' => ['santa semana', 'pascua'],
            'verano' => ['estival', 'estivales'],
            'invierno' => ['invernal', 'invernales']
        ];
        
        $expandedQueries = [$query, $normalizedQuery];
        
        // Agregar sinónimos si se encuentra una coincidencia
        foreach ($synonyms as $key => $values) {
            if (strpos($normalizedQuery, $key) !== false) {
                $expandedQueries = array_merge($expandedQueries, $values);
            }
        }
        
        return array_unique($expandedQueries);
    }

    /**
     * Calcula el score de relevancia para ordenamiento
     */
    private function calculateRelevanceScore($text, $query)
    {
        $textLower = mb_strtolower($text, 'UTF-8');
        $queryLower = mb_strtolower($query, 'UTF-8');
        
        // Exacta = 1, empieza con = 2, contiene = 3, no coincide = 4
        if ($textLower === $queryLower) {
            return 1;
        } elseif (strpos($textLower, $queryLower) === 0) {
            return 2;
        } elseif (strpos($textLower, $queryLower) !== false) {
            return 3;
        }
        
        return 4;
    }

    /**
     * Realiza búsqueda inteligente en localidades
     */
    private function searchLocalities($query)
    {
        $expandedQueries = $this->expandSearchQuery($query);
        
        // Obtener todas las localidades y filtrar en PHP para evitar consultas complejas
        $allLocalities = Locality::withCount('festivities')->get();
        
        $filteredLocalities = $allLocalities->filter(function ($locality) use ($expandedQueries) {
            $localityName = $locality->name;
            $normalizedLocalityName = $this->normalizeText($localityName);
            
            foreach ($expandedQueries as $searchTerm) {
                $normalizedTerm = $this->normalizeText($searchTerm);
                
                // Búsqueda exacta
                if (stripos($localityName, $searchTerm) !== false) {
                    return true;
                }
                
                // Búsqueda normalizada
                if (stripos($normalizedLocalityName, $normalizedTerm) !== false) {
                    return true;
                }
                
                // Búsqueda por palabras individuales
                $words = explode(' ', trim($searchTerm));
                foreach ($words as $word) {
                    if (strlen($word) > 2 && stripos($localityName, $word) !== false) {
                        return true;
                    }
                }
            }
            
            return false;
        });
        
        // Ordenar por relevancia
        $sortedLocalities = $filteredLocalities->sort(function ($a, $b) use ($query) {
            $aName = $this->normalizeText($a->name);
            $bName = $this->normalizeText($b->name);
            $queryLower = $this->normalizeText($query);
            
            // Prioridad: exacta > empieza con > contiene
            $aScore = $this->calculateRelevanceScore($aName, $queryLower);
            $bScore = $this->calculateRelevanceScore($bName, $queryLower);
            
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
        $expandedQueries = $this->expandSearchQuery($query);
        
        // Obtener todas las festividades y filtrar en PHP para evitar consultas complejas
        $allFestivities = Festivity::with(['locality', 'votes'])->get();
        
        $filteredFestivities = $allFestivities->filter(function ($festivity) use ($expandedQueries) {
            $festivityName = $festivity->name;
            $festivityDescription = $festivity->description ?? '';
            $localityName = $festivity->locality->name ?? '';
            
            $normalizedFestivityName = $this->normalizeText($festivityName);
            $normalizedDescription = $this->normalizeText($festivityDescription);
            $normalizedLocalityName = $this->normalizeText($localityName);
            
            foreach ($expandedQueries as $searchTerm) {
                $normalizedTerm = $this->normalizeText($searchTerm);
                
                // Búsqueda en nombre de festividad
                if (stripos($festivityName, $searchTerm) !== false || 
                    stripos($normalizedFestivityName, $normalizedTerm) !== false) {
                    return true;
                }
                
                // Búsqueda en descripción
                if (stripos($festivityDescription, $searchTerm) !== false || 
                    stripos($normalizedDescription, $normalizedTerm) !== false) {
                    return true;
                }
                
                // Búsqueda en nombre de localidad
                if (stripos($localityName, $searchTerm) !== false || 
                    stripos($normalizedLocalityName, $normalizedTerm) !== false) {
                    return true;
                }
                
                // Búsqueda por palabras individuales
                $words = explode(' ', trim($searchTerm));
                foreach ($words as $word) {
                    if (strlen($word) > 2) {
                        if (stripos($festivityName, $word) !== false || 
                            stripos($festivityDescription, $word) !== false || 
                            stripos($localityName, $word) !== false) {
                            return true;
                        }
                    }
                }
            }
            
            return false;
        });
        
        // Ordenar por relevancia
        $sortedFestivities = $filteredFestivities->sort(function ($a, $b) use ($query) {
            $aName = $this->normalizeText($a->name);
            $bName = $this->normalizeText($b->name);
            $queryLower = $this->normalizeText($query);
            
            $aScore = $this->calculateRelevanceScore($aName, $queryLower);
            $bScore = $this->calculateRelevanceScore($bName, $queryLower);
            
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
            'title' => 'FestiTowns - Festividades y Eventos Tradicionales de España',
            'description' => 'Descubre las mejores festividades y eventos tradicionales de España. Fallas de Valencia, San Fermín, Feria de Abril y muchas más. Información sobre horarios, eventos y tradiciones.',
            'keywords' => 'festividades españa, eventos tradicionales, fiestas populares, turismo cultural, fallas valencia, san fermin, feria abril',
            'url' => route('home'),
        ]);
        
        return view('home', compact('localities', 'upcomingFestivities', 'searchResults', 'searchType', 'searchQuery', 'searchDate', 'searchProvince', 'meta'));
    }
}
