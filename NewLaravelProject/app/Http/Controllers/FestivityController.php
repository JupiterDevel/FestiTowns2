<?php

namespace App\Http\Controllers;

use App\Models\Festivity;
use App\Models\Locality;
use App\Services\AdvertisementService;
use App\Services\GoogleMapsService;
use App\Services\SeoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class FestivityController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        private AdvertisementService $advertisementService,
        private GoogleMapsService $googleMapsService
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $festivities = Festivity::with('locality')->orderBy('start_date')->get();
        
        // SEO Meta Tags
        $meta = SeoService::generateMetaTags([
            'title' => 'Festividades de España - Calendario Completo | FestiTowns',
            'description' => 'Descubre todas las festividades tradicionales de España. Fallas de Valencia, San Fermín, Feria de Abril y muchas más. Calendario completo con fechas, eventos y tradiciones.',
            'keywords' => 'festividades españa, calendario festividades, fiestas tradicionales, eventos culturales españa',
            'url' => route('festivities.index'),
        ]);
        
        return view('festivities.index', compact('festivities', 'meta'));
    }

    /**
     * Get nearby festivities based on user coordinates.
     * Uses Haversine formula to calculate distance.
     */
    public function nearby(Request $request)
    {
        try {
            $request->validate([
                'latitude' => 'required|numeric|between:-90,90',
                'longitude' => 'required|numeric|between:-180,180',
                'radius' => 'nullable|numeric|min:1|max:500', // radius in kilometers
            ]);

            $latitude = $request->input('latitude');
            $longitude = $request->input('longitude');
            $radius = $request->input('radius', 50); // Default 50km

            // Check database driver
            $driver = config('database.default');
            $connection = config("database.connections.{$driver}.driver");
            
            if ($connection === 'sqlite') {
                // SQLite doesn't support radians/degrees functions, use a simpler approach
                // Get all festivities with coordinates and calculate distance in PHP
                $festivities = Festivity::with('locality')
                    ->whereNotNull('latitude')
                    ->whereNotNull('longitude')
                    ->get()
                    ->map(function ($festivity) use ($latitude, $longitude) {
                        $festivity->distance = $this->calculateDistance(
                            $latitude,
                            $longitude,
                            $festivity->latitude,
                            $festivity->longitude
                        );
                        return $festivity;
                    })
                    ->filter(function ($festivity) use ($radius) {
                        return $festivity->distance <= $radius;
                    })
                    ->sortBy('distance')
                    ->take(20)
                    ->values();
            } else {
                // MySQL/PostgreSQL: Use SQL Haversine formula
                $festivities = Festivity::with('locality')
                    ->whereNotNull('latitude')
                    ->whereNotNull('longitude')
                    ->selectRaw('*, (
                        6371 * acos(
                            cos(radians(?)) * 
                            cos(radians(latitude)) * 
                            cos(radians(longitude) - radians(?)) + 
                            sin(radians(?)) * 
                            sin(radians(latitude))
                        )
                    ) AS distance', [$latitude, $longitude, $latitude])
                    ->havingRaw('distance <= ?', [$radius])
                    ->orderBy('distance')
                    ->limit(20)
                    ->get();
            }

            return response()->json([
                'success' => true,
                'festivities' => $festivities->map(function ($festivity) {
                    return [
                        'id' => $festivity->id,
                        'name' => $festivity->name,
                        'slug' => $festivity->slug,
                        'description' => Str::limit($festivity->description, 150),
                        'start_date' => $festivity->start_date->format('Y-m-d'),
                        'end_date' => $festivity->end_date ? $festivity->end_date->format('Y-m-d') : null,
                        'latitude' => $festivity->latitude,
                        'longitude' => $festivity->longitude,
                        'distance' => round($festivity->distance, 2),
                        'locality' => [
                            'name' => $festivity->locality->name ?? null,
                            'province' => $festivity->province ?? null,
                        ],
                        'photo' => $festivity->photos && count($festivity->photos) > 0 ? $festivity->photos[0] : null,
                        'url' => route('festivities.show', $festivity),
                    ];
                }),
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error in nearby festivities: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Calculate distance between two coordinates using Haversine formula
     * Returns distance in kilometers
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // Earth's radius in kilometers

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c;

        return $distance;
    }

    /**
     * Get festivities for map display (by province and/or map bounds)
     */
    public function forMap(Request $request)
    {
        try {
            $request->validate([
                'province' => 'nullable|string|in:' . implode(',', config('provinces.provinces')),
                'north' => 'nullable|numeric',
                'south' => 'nullable|numeric',
                'east' => 'nullable|numeric',
                'west' => 'nullable|numeric',
            ]);

            $query = Festivity::with('locality')
                ->whereNotNull('latitude')
                ->whereNotNull('longitude');

            // Filter by province if provided
            if ($request->has('province') && $request->province) {
                $query->where(function ($q) use ($request) {
                    $q->where('province', $request->province)
                      ->orWhereHas('locality', function ($localityQuery) use ($request) {
                          $localityQuery->where('province', $request->province);
                      });
                });
            }

            // Filter by map bounds if provided
            if ($request->has(['north', 'south', 'east', 'west'])) {
                $query->whereBetween('latitude', [
                    min($request->south, $request->north),
                    max($request->south, $request->north)
                ])->whereBetween('longitude', [
                    min($request->west, $request->east),
                    max($request->west, $request->east)
                ]);
            }

            $festivities = $query->withCount('votes')
                ->orderBy('votes_count', 'desc')
                ->orderBy('start_date', 'asc') // Secondary sort by date
                ->limit(50)
                ->get();

            return response()->json([
                'success' => true,
                'festivities' => $festivities->map(function ($festivity) {
                    return [
                        'id' => $festivity->id,
                        'name' => $festivity->name,
                        'slug' => $festivity->slug,
                        'description' => Str::limit($festivity->description, 150),
                        'start_date' => $festivity->start_date->format('Y-m-d'),
                        'end_date' => $festivity->end_date ? $festivity->end_date->format('Y-m-d') : null,
                        'latitude' => $festivity->latitude,
                        'longitude' => $festivity->longitude,
                        'locality' => [
                            'name' => $festivity->locality->name ?? null,
                            'province' => $festivity->province ?? null,
                        ],
                        'photo' => $festivity->photos && count($festivity->photos) > 0 ? $festivity->photos[0] : null,
                        'url' => route('festivities.show', $festivity),
                    ];
                }),
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error in map festivities: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        try {
            $this->authorize('create', Festivity::class);
            
            $user = Auth::user();
            
            // Si el usuario es townhall, debe venir desde una localidad
            if ($user->isTownHall()) {
                if (!$request->has('locality_id')) {
                    return redirect()->route('festivities.index')
                        ->with('error', 'Los usuarios con rol de ayuntamiento solo pueden añadir festividades desde la vista de su localidad.');
                }
                
                $locality = Locality::find($request->get('locality_id'));
                
                // Verificar que la localidad pertenece al usuario townhall
                if (!$locality || $locality->id !== $user->locality_id) {
                    return redirect()->route('festivities.index')
                        ->with('error', 'Solo puedes añadir festividades para tu localidad asignada.');
                }
            } else {
                $locality = null;
                if ($request->has('locality_id')) {
                    $locality = Locality::find($request->get('locality_id'));
                }
            }
            
            return view('festivities.create', compact('locality'));
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return redirect()->route('festivities.index')
                ->with('error', 'You do not have permission to create festivities.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $this->authorize('create', Festivity::class);
            
            $user = Auth::user();
            
            $validated = $request->validate([
                'locality_name' => 'required|string|max:255',
                'province' => 'required|string|in:' . implode(',', config('provinces.provinces')),
                'name' => 'required|string|max:255',
                'start_date' => 'required|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
                'description' => 'required|string',
                'photos' => 'nullable|array|max:10',
                'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
                'google_maps_url' => 'nullable|url|max:500',
            ]);

            // Si el usuario es townhall, validar que está creando para su localidad
            if ($user->isTownHall()) {
                if (!$user->locality_id) {
                    return redirect()->back()
                        ->with('error', 'No tienes una localidad asignada. Contacta con un administrador.')
                        ->withInput();
                }
                
                // Verificar que la localidad que está creando coincide con la suya
                $userLocality = Locality::find($user->locality_id);
                if (!$userLocality || 
                    ($userLocality->name !== $validated['locality_name'] || 
                     $userLocality->province !== $validated['province'])) {
                    return redirect()->back()
                        ->with('error', 'Solo puedes añadir festividades para tu localidad asignada.')
                        ->withInput();
                }
            }

            // Find or create the locality
            $locality = Locality::firstOrCreate(
                ['name' => $validated['locality_name'], 'province' => $validated['province']],
                [
                    'name' => $validated['locality_name'],
                    'address' => $validated['locality_name'], // Use name as address if not provided
                    'province' => $validated['province'],
                    'description' => 'Auto-created locality for festivity',
                    'places_of_interest' => '',
                    'monuments' => ''
                ]
            );
            
            // Si es townhall, asegurar que la localidad encontrada/creada es la suya
            if ($user->isTownHall() && $locality->id !== $user->locality_id) {
                return redirect()->back()
                    ->with('error', 'Solo puedes añadir festividades para tu localidad asignada.')
                    ->withInput();
            }

            // Process photos
            $photos = [];
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $photo) {
                    $filename = time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
                    $path = $photo->storeAs('festivities', $filename, 'public');
                    $photos[] = '/storage/festivities/' . $filename;
                }
            }
            
            // Process Google Maps URL and extract coordinates
            $googleMapsUrl = $validated['google_maps_url'] ?? null;
            $coordinates = null;
            
            if ($googleMapsUrl) {
                $coordinates = $this->googleMapsService->extractCoordinatesFromUrl($googleMapsUrl);
            }
            
            // Create festivity with locality_id
            $festivityData = $validated;
            $festivityData['locality_id'] = $locality->id;
            $festivityData['photos'] = $photos;
            $festivityData['google_maps_url'] = $googleMapsUrl;
            $festivityData['latitude'] = $coordinates['latitude'] ?? null;
            $festivityData['longitude'] = $coordinates['longitude'] ?? null;
            unset($festivityData['locality_name']);
            
            Festivity::create($festivityData);

            // Redirigir según el origen
            if ($user->isTownHall() && $locality) {
                return redirect()->route('localities.show', $locality)
                    ->with('success', 'Festividad creada exitosamente.');
            }

            return redirect()->route('festivities.index')
                ->with('success', 'Festivity created successfully.');
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return redirect()->back()
                ->with('error', 'You do not have permission to create festivities.')
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'An error occurred while creating the festivity: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Festivity $festivity)
    {
        $festivity->load(['locality', 'approvedComments.user', 'events'])->loadCount('votes');
        
        // Verificar si el usuario ya votó hoy (los administradores siempre pueden votar)
        $userVotedToday = false;
        $visitPointsEarned = false;
        
        if (Auth::check()) {
            $user = Auth::user();
            
            // Los administradores pueden votar múltiples veces, así que siempre pueden votar
            // Visitor y TownHall solo pueden votar una vez al día
            if ($user->isVisitor() || $user->isTownHall()) {
                $userVotedToday = \App\Models\Vote::where('user_id', Auth::id())
                    ->whereDate('voted_at', now()->toDateString())
                    ->exists();
            }
            
            // Otorgar puntos por visitar festividades de otras localidades (solo visitantes)
            if ($user->isVisitor() && $user->canEarnVisitPoints($festivity)) {
                $user->addPoints(1); // 1 punto por visitar festividad de otra localidad
                $user->markVisitedToday($festivity);
                $visitPointsEarned = true;
            }
        }
        
        // SEO Meta Tags
        $image = $festivity->photos && count($festivity->photos) > 0 
            ? (filter_var($festivity->photos[0], FILTER_VALIDATE_URL) ? $festivity->photos[0] : url($festivity->photos[0]))
            : asset('favicon.ico');
        
        $meta = SeoService::generateMetaTags([
            'title' => SeoService::generateFestivityTitle($festivity),
            'description' => SeoService::generateFestivityDescription($festivity),
            'keywords' => SeoService::generateKeywords('festivity', [
                'name' => $festivity->name,
                'locality' => $festivity->locality->name ?? '',
                'province' => $festivity->province ?? '',
            ]),
            'image' => $image,
            'url' => route('festivities.show', $festivity),
            'type' => 'article',
        ]);
        
        // Schema.org JSON-LD
        $schema = SeoService::generateEventSchema($festivity);

        $ads = $this->advertisementService->forFestivity($festivity);
        $adCreationParams = [
            'festivity_id' => $festivity->id,
            'locality_id' => $festivity->locality_id,
        ];
        
        return view('festivities.show', [
            'festivity' => $festivity,
            'userVotedToday' => $userVotedToday,
            'visitPointsEarned' => $visitPointsEarned,
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
    public function edit(Festivity $festivity)
    {
        $this->authorize('update', $festivity);
        
        return view('festivities.edit', compact('festivity'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Festivity $festivity)
    {
        $this->authorize('update', $festivity);
        
            $validated = $request->validate([
                'locality_name' => 'required|string|max:255',
                'province' => 'required|string|in:' . implode(',', config('provinces.provinces')),
                'name' => 'required|string|max:255',
                'start_date' => 'required|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
                'description' => 'required|string',
                'photos' => 'nullable|array|max:10',
                'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
                'existing_photos' => 'nullable|array',
                'google_maps_url' => 'nullable|url|max:500',
            ]);

        // Find or create the locality
        $locality = Locality::firstOrCreate(
            ['name' => $validated['locality_name'], 'province' => $validated['province']],
            [
                'name' => $validated['locality_name'],
                'address' => $validated['locality_name'], // Use name as address if not provided
                'province' => $validated['province'],
                'description' => 'Auto-created locality for festivity',
                'places_of_interest' => '',
                'monuments' => ''
            ]
        );

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
                $path = $photo->storeAs('festivities', $filename, 'public');
                $photos[] = '/storage/festivities/' . $filename;
            }
        }

        // Process Google Maps URL and extract coordinates
        $googleMapsUrl = $validated['google_maps_url'] ?? null;
        $coordinates = null;
        
        if ($googleMapsUrl) {
            $coordinates = $this->googleMapsService->extractCoordinatesFromUrl($googleMapsUrl);
        }
        
        // Update festivity with locality_id
        $festivityData = $validated;
        $festivityData['locality_id'] = $locality->id;
        $festivityData['photos'] = $photos;
        $festivityData['google_maps_url'] = $googleMapsUrl;
        $festivityData['latitude'] = $coordinates['latitude'] ?? null;
        $festivityData['longitude'] = $coordinates['longitude'] ?? null;
        unset($festivityData['locality_name']);
        
        $festivity->update($festivityData);

        return redirect()->route('festivities.show', $festivity)
            ->with('success', 'Festivity updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Festivity $festivity)
    {
        $this->authorize('delete', $festivity);
        
        $festivity->delete();

        return redirect()->route('festivities.index')
            ->with('success', 'Festivity deleted successfully.');
    }
}
