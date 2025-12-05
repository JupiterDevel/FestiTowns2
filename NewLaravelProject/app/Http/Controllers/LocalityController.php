<?php

namespace App\Http\Controllers;

use App\Models\Locality;
use App\Services\AdvertisementService;
use App\Services\SeoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class LocalityController extends Controller
{
    use AuthorizesRequests;

    public function __construct(private AdvertisementService $advertisementService)
    {
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
        
        // Step 1: Get localities with ACTIVE festivities (today or this week)
        $activeLocalities = Locality::with(['festivities' => function ($query) use ($today, $endOfWeek) {
                $query->where('start_date', '<=', $endOfWeek)
                      ->where(function ($endQuery) use ($today) {
                          $endQuery->whereNull('end_date')
                                   ->orWhere('end_date', '>=', $today);
                      });
            }])
            ->whereHas('festivities', function ($query) use ($today, $endOfWeek) {
                $query->where('start_date', '<=', $endOfWeek)
                      ->where(function ($endQuery) use ($today) {
                          $endQuery->whereNull('end_date')
                                   ->orWhere('end_date', '>=', $today);
                      });
            })
            ->get()
            ->map(function ($locality) {
                $totalVotes = $locality->festivities->sum(function ($festivity) {
                    return $festivity->votes()->count();
                });
                $locality->total_votes = $totalVotes;
                $locality->priority = 1; // Highest priority
                return $locality;
            })
            ->sortByDesc('total_votes');
        
        $neededCount = $perPage * $currentPage;
        $finalLocalities = collect($activeLocalities);
        
        // Step 2: If we need more localities, get those with UPCOMING festivities with PAID ADS
        if ($finalLocalities->count() < $neededCount) {
            $upcomingWithAds = Locality::with(['festivities' => function ($query) use ($today) {
                    $query->where('start_date', '>', $today)
                          ->whereHas('advertisements', function ($adQuery) {
                              $adQuery->where('premium', true)
                                      ->where('active', true);
                          });
                }])
                ->whereHas('festivities', function ($query) use ($today) {
                    $query->where('start_date', '>', $today)
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
        }
        
        // Step 3: If we STILL need more, get random localities
        if ($finalLocalities->count() < $neededCount) {
            $randomLocalities = Locality::with('festivities')
                ->whereNotIn('id', $finalLocalities->pluck('id'))
                ->inRandomOrder()
                ->limit($neededCount - $finalLocalities->count())
                ->get()
                ->map(function ($locality) {
                    $locality->total_votes = 0;
                    $locality->priority = 3; // Lowest priority
                    return $locality;
                });
            
            $finalLocalities = $finalLocalities->merge($randomLocalities);
        }
        
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
            'title' => 'Localidades de España - Festividades y Turismo | FestiTowns',
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
     * Search localities via AJAX
     */
    public function search(Request $request)
    {
        $today = now();
        $endOfWeek = now()->endOfWeek();
        $perPage = 6;
        $page = $request->input('page', 1);
        
        // Build base query with filters
        $baseQuery = Locality::query();
        
        // Search by name or description
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $baseQuery->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%')
                  ->orWhere('address', 'like', '%' . $searchTerm . '%');
            });
        }
        
        // Filter by province
        if ($request->filled('province')) {
            $baseQuery->where('province', $request->input('province'));
        }
        
        // Get all matching localities
        $allMatchingIds = $baseQuery->pluck('id');
        
        // Step 1: Get localities with ACTIVE festivities from matching results
        $activeLocalities = Locality::with(['festivities'])
            ->whereIn('id', $allMatchingIds)
            ->whereHas('festivities', function ($query) use ($today, $endOfWeek) {
                $query->where('start_date', '<=', $endOfWeek)
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
                $locality->priority = 1;
                return $locality;
            })
            ->sortByDesc('total_votes');
        
        $finalLocalities = collect($activeLocalities);
        $neededCount = $perPage * $page;
        
        // Step 2: Fill with upcoming festivities with paid ads
        if ($finalLocalities->count() < $neededCount) {
            $upcomingWithAds = Locality::with(['festivities'])
                ->whereIn('id', $allMatchingIds)
                ->whereNotIn('id', $finalLocalities->pluck('id'))
                ->whereHas('festivities', function ($query) use ($today) {
                    $query->where('start_date', '>', $today)
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
        }
        
        // Step 3: Fill with random matching localities
        if ($finalLocalities->count() < $neededCount) {
            $remainingLocalities = Locality::with(['festivities'])
                ->whereIn('id', $allMatchingIds)
                ->whereNotIn('id', $finalLocalities->pluck('id'))
                ->limit($neededCount - $finalLocalities->count())
                ->get()
                ->map(function ($locality) {
                    $locality->active_festivities = collect();
                    $locality->total_votes = 0;
                    $locality->priority = 3;
                    return $locality;
                });
            
            $finalLocalities = $finalLocalities->merge($remainingLocalities);
        }
        
        // Format for JSON response
        $formattedLocalities = $finalLocalities->map(function ($locality) use ($today) {
            $activeFestivitiesCount = $locality->active_festivities->count();
            
            $nextFestivity = null;
            if ($activeFestivitiesCount === 0) {
                $nextFestivity = $locality->festivities()
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
    public function show(Locality $locality)
    {
        $locality->load('festivities');
        
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
