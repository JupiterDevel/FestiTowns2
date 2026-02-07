<?php

namespace App\Http\Controllers;

use App\Models\Locality;
use App\Services\AdvertisementService;
use App\Services\SearchService;
use App\Services\SeoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class LocalityController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        private AdvertisementService $advertisementService,
        private SearchService $searchService
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // If it's an AJAX request, return JSON
        if ($request->ajax() || $request->wantsJson()) {
            return $this->search($request);
        }
        
        $today = now();
        $endOfWeek = now()->endOfWeek();
        $perPage = 6;
        $currentPage = $request->input('page', 1);
        
        // Check if user is admin (admins can see all festivities)
        $isAdmin = auth()->check() && auth()->user()->isAdmin();
        
        // Step 1: Get localities with ACTIVE festivities (today or this week)
        // Priority 1.1: Active festivities WITH paid ads
        $activeWithAds = Locality::with(['festivities' => function ($query) use ($today, $endOfWeek, $isAdmin) {
                $query->when(!$isAdmin, function ($q) {
                    $q->where('approved', true);
                })
                      ->where('start_date', '<=', $endOfWeek)
                      ->where(function ($endQuery) use ($today) {
                          $endQuery->whereNull('end_date')
                                   ->orWhere('end_date', '>=', $today);
                      })
                      ->whereHas('advertisements', function ($adQuery) {
                          $adQuery->where('premium', true)
                                  ->where('active', true);
                      });
            }])
            ->whereHas('festivities', function ($query) use ($today, $endOfWeek, $isAdmin) {
                $query->when(!$isAdmin, function ($q) {
                    $q->where('approved', true);
                })
                      ->where('start_date', '<=', $endOfWeek)
                      ->where(function ($endQuery) use ($today) {
                          $endQuery->whereNull('end_date')
                                   ->orWhere('end_date', '>=', $today);
                      })
                      ->whereHas('advertisements', function ($adQuery) {
                          $adQuery->where('premium', true)
                                  ->where('active', true);
                      });
            })
            ->get()
            ->map(function ($locality) {
                $totalVotes = $locality->festivities->sum(function ($festivity) {
                    return $festivity->votes()->count();
                });
                $locality->total_votes = $totalVotes;
                $locality->priority = 1.1; // Highest priority - active with ads
                return $locality;
            })
            ->sortByDesc('total_votes');
        
        // Priority 1.2: Active festivities WITHOUT paid ads
        $activeWithoutAds = Locality::with(['festivities' => function ($query) use ($today, $endOfWeek, $isAdmin) {
                $query->when(!$isAdmin, function ($q) {
                    $q->where('approved', true);
                })
                      ->where('start_date', '<=', $endOfWeek)
                      ->where(function ($endQuery) use ($today) {
                          $endQuery->whereNull('end_date')
                                   ->orWhere('end_date', '>=', $today);
                      });
            }])
            ->whereHas('festivities', function ($query) use ($today, $endOfWeek, $isAdmin) {
                $query->when(!$isAdmin, function ($q) {
                    $q->where('approved', true);
                })
                      ->where('start_date', '<=', $endOfWeek)
                      ->where(function ($endQuery) use ($today) {
                          $endQuery->whereNull('end_date')
                                   ->orWhere('end_date', '>=', $today);
                      });
            })
            ->whereNotIn('id', $activeWithAds->pluck('id'))
            ->get()
            ->map(function ($locality) {
                $totalVotes = $locality->festivities->sum(function ($festivity) {
                    return $festivity->votes()->count();
                });
                $locality->total_votes = $totalVotes;
                $locality->priority = 1.2; // High priority - active without ads
                return $locality;
            })
            ->sortByDesc('total_votes');
        
        // Combine active localities (with ads first, then without ads)
        $activeLocalities = $activeWithAds->merge($activeWithoutAds);
        
        $finalLocalities = collect($activeLocalities);
        
        // Step 2: Get all localities with UPCOMING festivities with PAID ADS
        $upcomingWithAds = Locality::with(['festivities' => function ($query) use ($today, $isAdmin) {
                $query->when(!$isAdmin, function ($q) {
                    $q->where('approved', true);
                })
                      ->where('start_date', '>', $today)
                      ->whereHas('advertisements', function ($adQuery) {
                          $adQuery->where('premium', true)
                                  ->where('active', true);
                      });
            }])
            ->whereHas('festivities', function ($query) use ($today, $isAdmin) {
                $query->when(!$isAdmin, function ($q) {
                    $q->where('approved', true);
                })
                      ->where('start_date', '>', $today)
                      ->whereHas('advertisements', function ($adQuery) {
                          $adQuery->where('premium', true)
                                  ->where('active', true);
                      });
            })
            ->whereNotIn('id', $finalLocalities->pluck('id'))
            ->get()
            ->map(function ($locality) {
                $locality->total_votes = 0;
                $locality->priority = 2; // Medium priority
                return $locality;
            })
            ->sortBy('festivities.0.start_date'); // Sort by soonest festivity
        
        $finalLocalities = $finalLocalities->merge($upcomingWithAds);
        
        // Step 3: Get all remaining random localities
        $randomLocalities = Locality::with(['festivities' => function ($query) use ($isAdmin) {
                $query->when(!$isAdmin, function ($q) {
                    $q->where('approved', true);
                });
            }])
            ->whereNotIn('id', $finalLocalities->pluck('id'))
            ->inRandomOrder()
            ->get()
            ->map(function ($locality) {
                $locality->total_votes = 0;
                $locality->priority = 3; // Lowest priority
                return $locality;
            });
        
        $finalLocalities = $finalLocalities->merge($randomLocalities);
        
        // Paginate
        $paginatedLocalities = new \Illuminate\Pagination\LengthAwarePaginator(
            $finalLocalities->forPage($currentPage, $perPage),
            $finalLocalities->count(),
            $perPage,
            $currentPage,
            ['path' => route('localities.index')]
        );
        
        $provinces = config('provinces.provinces');
        
        // SEO Meta Tags
        $meta = SeoService::generateMetaTags([
            'title' => 'Localidades de España - Festividades y Turismo | ElAlmaDeLasFiestas',
            'description' => 'Explora las localidades españolas y descubre sus festividades tradicionales, lugares de interés y monumentos. Planifica tu visita y conoce la cultura de España.',
            'keywords' => 'localidades españa, turismo españa, pueblos españa, ciudades españa, festividades por localidad',
            'url' => route('localities.index'),
        ]);
        
        return view('localities.index', [
            'localities' => $paginatedLocalities,
            'meta' => $meta,
            'provinces' => $provinces,
            'isSearching' => false,
        ]);
    }

    /**
     * Búsqueda de localidades vía AJAX con búsqueda inteligente.
     * 
     * Implementa búsqueda avanzada con:
     * - Normalización de acentos (ej: "Valencia" encuentra "València")
     * - Expansión con sinónimos (ej: "pueblo" encuentra "localidad", "municipio", "ciudad")
     * - Ordenamiento por relevancia (exacta > empieza con > contiene)
     * - Búsqueda en múltiples campos (nombre, descripción, dirección)
     * 
     * Mantiene la lógica de priorización existente:
     * - Localidades con festividades activas y anuncios pagados (prioridad 1.1)
     * - Localidades con festividades activas sin anuncios (prioridad 1.2)
     * - Localidades con festividades próximas con anuncios (prioridad 2)
     * - Otras localidades (prioridad 3)
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $today = now();
        $endOfWeek = now()->endOfWeek();
        $perPage = 6;
        $page = $request->input('page', 1);
        
        // Check if user is admin (admins can see all festivities)
        $isAdmin = auth()->check() && auth()->user()->isAdmin();
        
        // Get all localities for intelligent filtering
        $allLocalities = Locality::with(['festivities' => function ($query) use ($isAdmin) {
                $query->when(!$isAdmin, function ($q) {
                    $q->where('approved', true);
                });
            }])->get();
        
        // Apply intelligent search if search term is provided
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $expandedQueries = $this->searchService->expandSearchQuery($searchTerm);
            
            // Filter localities using intelligent search
            $allLocalities = $allLocalities->filter(function ($locality) use ($expandedQueries) {
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
        }
        
        // Filter by province
        if ($request->filled('province')) {
            $allLocalities = $allLocalities->filter(function ($locality) use ($request) {
                return $locality->province === $request->input('province');
            });
        }
        
        // Get all matching IDs
        $allMatchingIds = $allLocalities->pluck('id');
        
        // Step 1: Get localities with ACTIVE festivities from matching results
        // Priority 1.1: Active festivities WITH paid ads
        $activeWithAds = Locality::with(['festivities' => function ($query) use ($isAdmin) {
                $query->when(!$isAdmin, function ($q) {
                    $q->where('approved', true);
                });
            }])
            ->whereIn('id', $allMatchingIds)
            ->whereHas('festivities', function ($query) use ($today, $endOfWeek, $isAdmin) {
                $query->when(!$isAdmin, function ($q) {
                    $q->where('approved', true);
                })
                      ->where('start_date', '<=', $endOfWeek)
                      ->where(function ($endQuery) use ($today) {
                          $endQuery->whereNull('end_date')
                                   ->orWhere('end_date', '>=', $today);
                      })
                      ->whereHas('advertisements', function ($adQuery) {
                          $adQuery->where('premium', true)->where('active', true);
                      });
            })
            ->get()
            ->map(function ($locality) use ($today, $endOfWeek) {
                $activeFestivities = $locality->festivities->filter(function ($festivity) use ($today, $endOfWeek) {
                    return $festivity->start_date <= $endOfWeek &&
                           ($festivity->end_date === null || $festivity->end_date >= $today);
                });
                
                $totalVotes = $activeFestivities->sum(function ($festivity) {
                    return $festivity->votes()->count();
                });
                
                $locality->active_festivities = $activeFestivities;
                $locality->total_votes = $totalVotes;
                $locality->priority = 1.1; // Highest priority - active with ads
                return $locality;
            })
            ->sortByDesc('total_votes');
        
        // Priority 1.2: Active festivities WITHOUT paid ads
        $activeWithoutAds = Locality::with(['festivities' => function ($query) use ($isAdmin) {
                $query->when(!$isAdmin, function ($q) {
                    $q->where('approved', true);
                });
            }])
            ->whereIn('id', $allMatchingIds)
            ->whereNotIn('id', $activeWithAds->pluck('id'))
            ->whereHas('festivities', function ($query) use ($today, $endOfWeek, $isAdmin) {
                $query->when(!$isAdmin, function ($q) {
                    $q->where('approved', true);
                })
                      ->where('start_date', '<=', $endOfWeek)
                      ->where(function ($endQuery) use ($today) {
                          $endQuery->whereNull('end_date')
                                   ->orWhere('end_date', '>=', $today);
                      });
            })
            ->get()
            ->map(function ($locality) use ($today, $endOfWeek) {
                $activeFestivities = $locality->festivities->filter(function ($festivity) use ($today, $endOfWeek) {
                    return $festivity->start_date <= $endOfWeek &&
                           ($festivity->end_date === null || $festivity->end_date >= $today);
                });
                
                $totalVotes = $activeFestivities->sum(function ($festivity) {
                    return $festivity->votes()->count();
                });
                
                $locality->active_festivities = $activeFestivities;
                $locality->total_votes = $totalVotes;
                $locality->priority = 1.2; // High priority - active without ads
                return $locality;
            })
            ->sortByDesc('total_votes');
        
        // Combine active localities (with ads first, then without ads)
        $activeLocalities = $activeWithAds->merge($activeWithoutAds);
        
        $finalLocalities = collect($activeLocalities);
        
        // Step 2: Get all upcoming festivities with paid ads
        $upcomingWithAds = Locality::with(['festivities' => function ($query) use ($isAdmin) {
                $query->when(!$isAdmin, function ($q) {
                    $q->where('approved', true);
                });
            }])
            ->whereIn('id', $allMatchingIds)
            ->whereNotIn('id', $finalLocalities->pluck('id'))
            ->whereHas('festivities', function ($query) use ($today, $isAdmin) {
                $query->when(!$isAdmin, function ($q) {
                    $q->where('approved', true);
                })
                      ->where('start_date', '>', $today)
                      ->whereHas('advertisements', function ($adQuery) {
                          $adQuery->where('premium', true)->where('active', true);
                      });
            })
            ->get()
            ->map(function ($locality) use ($today) {
                $locality->active_festivities = collect();
                $locality->total_votes = 0;
                $locality->priority = 2;
                return $locality;
            });
        
        $finalLocalities = $finalLocalities->merge($upcomingWithAds);
        
        // Step 3: Get all remaining matching localities
        $remainingLocalities = Locality::with(['festivities' => function ($query) use ($isAdmin) {
                $query->when(!$isAdmin, function ($q) {
                    $q->where('approved', true);
                });
            }])
            ->whereIn('id', $allMatchingIds)
            ->whereNotIn('id', $finalLocalities->pluck('id'))
            ->get()
            ->map(function ($locality) {
                $locality->active_festivities = collect();
                $locality->total_votes = 0;
                $locality->priority = 3;
                return $locality;
            });
        
        $finalLocalities = $finalLocalities->merge($remainingLocalities);
        
        // Sort by relevance if search term is provided
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $finalLocalities = $finalLocalities->sort(function ($a, $b) use ($searchTerm) {
                $aName = $this->searchService->normalizeText($a->name);
                $bName = $this->searchService->normalizeText($b->name);
                $queryLower = $this->searchService->normalizeText($searchTerm);
                
                // Calculate relevance scores
                $aScore = $this->searchService->calculateRelevanceScore($aName, $queryLower);
                $bScore = $this->searchService->calculateRelevanceScore($bName, $queryLower);
                
                // If same relevance, maintain priority order
                if ($aScore === $bScore) {
                    // Maintain existing priority order (1.1 > 1.2 > 2 > 3)
                    $priorityDiff = ($a->priority ?? 4) - ($b->priority ?? 4);
                    if ($priorityDiff !== 0) {
                        return $priorityDiff;
                    }
                    // If same priority, sort by name
                    return strcmp($a->name, $b->name);
                }
                
                return $aScore - $bScore;
            });
        }
        
        // Format for JSON response
        $formattedLocalities = $finalLocalities->map(function ($locality) use ($today, $isAdmin) {
            $activeFestivitiesCount = $locality->active_festivities->count();
            
            $nextFestivity = null;
            if ($activeFestivitiesCount === 0) {
                $nextFestivity = $locality->festivities()
                    ->when(!$isAdmin, function ($query) {
                        $query->where('approved', true);
                    })
                    ->where('start_date', '>', $today)
                    ->orderBy('start_date', 'asc')
                    ->first();
            }
            
            return [
                'id' => $locality->id,
                'name' => $locality->name,
                'slug' => $locality->slug,
                'address' => $locality->address,
                'province' => $locality->province,
                'description' => $locality->description,
                'photos' => $locality->photos,
                'active_festivities_count' => $activeFestivitiesCount,
                'total_votes' => $locality->total_votes,
                'next_festivity' => $nextFestivity ? [
                    'name' => $nextFestivity->name,
                    'start_date' => $nextFestivity->start_date->format('d M Y'),
                ] : null,
                'show_url' => route('localities.show', $locality),
                'festivities_url' => route('festivities.index') . '?locality=' . $locality->slug,
                'edit_url' => route('localities.edit', $locality),
                'delete_url' => route('localities.destroy', $locality),
            ];
        });
        
        // Paginate
        $paginatedData = $formattedLocalities->forPage($page, $perPage)->values();
        
        return response()->json([
            'success' => true,
            'localities' => $paginatedData,
            'pagination' => [
                'current_page' => $page,
                'last_page' => ceil($formattedLocalities->count() / $perPage),
                'total' => $formattedLocalities->count(),
                'per_page' => $perPage,
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $this->authorize('create', Locality::class);
            return view('localities.create');
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return redirect()->route('localities.index')
                ->with('error', 'You do not have permission to create localities. Only administrators can create new localities.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $this->authorize('create', Locality::class);
            
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'address' => 'required|string|max:255',
                'province' => 'required|string|in:' . implode(',', config('provinces.provinces')),
                'description' => 'required|string',
                'places_of_interest' => 'required|string',
                'monuments' => 'required|string',
                'photos' => 'nullable|array|max:10',
                'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            ]);

            // Process photos
            $photos = [];
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $photo) {
                    $filename = time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
                    $path = $photo->storeAs('localities', $filename, 'public');
                    $photos[] = '/storage/localities/' . $filename;
                }
            }
            
            $validated['photos'] = $photos;
            Locality::create($validated);

            return redirect()->route('localities.index')
                ->with('success', 'Locality created successfully.');
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return redirect()->back()
                ->with('error', 'You do not have permission to create localities.')
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'An error occurred while creating the locality: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Locality $locality, Request $request)
    {
        // Check if user is admin (admins can see all festivities)
        $isAdmin = auth()->check() && auth()->user()->isAdmin();
        
        // Paginate festivities
        $perPage = 6; // 2 rows x 3 columns = 6 items per page
        $festivities = $locality->festivities()
            ->when(!$isAdmin, function ($query) {
                $query->where('approved', true);
            })
            ->withCount('votes')
            ->orderBy('start_date', 'asc')
            ->paginate($perPage, ['*'], 'festivities_page')
            ->appends($request->except('festivities_page'));
        
        // SEO Meta Tags
        $image = $locality->photos && count($locality->photos) > 0 
            ? (filter_var($locality->photos[0], FILTER_VALIDATE_URL) ? $locality->photos[0] : url($locality->photos[0]))
            : asset('favicon.ico');
        
        $meta = SeoService::generateMetaTags([
            'title' => SeoService::generateLocalityTitle($locality),
            'description' => SeoService::generateLocalityDescription($locality),
            'keywords' => SeoService::generateKeywords('locality', [
                'name' => $locality->name,
                'province' => $locality->province ?? '',
            ]),
            'image' => $image,
            'url' => route('localities.show', $locality),
            'type' => 'article',
        ]);
        
        // Schema.org JSON-LD
        $schema = SeoService::generateCitySchema($locality);

        $ads = $this->advertisementService->forLocality($locality);
        $adCreationParams = [
            'locality_id' => $locality->id,
        ];
        
        return view('localities.show', [
            'locality' => $locality,
            'festivities' => $festivities,
            'meta' => $meta,
            'schema' => $schema,
            'mainAdvertisement' => $ads['main'],
            'secondaryAdvertisements' => $ads['secondary'],
            'adCreationParams' => $adCreationParams,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Locality $locality)
    {
        $this->authorize('update', $locality);
        return view('localities.edit', compact('locality'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Locality $locality)
    {
        $this->authorize('update', $locality);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'province' => 'required|string|in:' . implode(',', config('provinces.provinces')),
            'description' => 'required|string',
            'places_of_interest' => 'required|string',
            'monuments' => 'required|string',
            'photos' => 'nullable|array|max:10',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'existing_photos' => 'nullable|array',
        ]);

        // Process photos - combine existing and new
        $photos = [];
        
        // Keep existing photos that weren't removed
        if ($request->has('existing_photos')) {
            $photos = array_merge($photos, $request->input('existing_photos', []));
        }
        
        // Add new photos
        $newPhotosCount = $request->hasFile('photos') ? count($request->file('photos')) : 0;
        $totalPhotos = count($photos) + $newPhotosCount;
        
        if ($totalPhotos > 10) {
            return redirect()->back()
                ->with('error', 'No puedes tener más de 10 fotos en total.')
                ->withInput();
        }
        
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $filename = time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
                $path = $photo->storeAs('localities', $filename, 'public');
                $photos[] = '/storage/localities/' . $filename;
            }
        }
        
        $validated['photos'] = $photos;
        $locality->update($validated);

        return redirect()->route('localities.show', $locality)
            ->with('success', 'Locality updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Locality $locality)
    {
        $this->authorize('delete', $locality);
        
        $locality->delete();

        return redirect()->route('localities.index')
            ->with('success', 'Locality deleted successfully.');
    }
}
