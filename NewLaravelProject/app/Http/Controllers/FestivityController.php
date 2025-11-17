<?php

namespace App\Http\Controllers;

use App\Models\Festivity;
use App\Models\Locality;
use App\Services\AdvertisementService;
use App\Services\SeoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class FestivityController extends Controller
{
    use AuthorizesRequests;

    public function __construct(private AdvertisementService $advertisementService)
    {
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
            
            // Create festivity with locality_id
            $festivityData = $validated;
            $festivityData['locality_id'] = $locality->id;
            $festivityData['photos'] = $photos;
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
        
        // Verificar si el usuario ya votó hoy
        $userVotedToday = false;
        $visitPointsEarned = false;
        
        if (Auth::check()) {
            $user = Auth::user();
            
            $userVotedToday = \App\Models\Vote::where('user_id', Auth::id())
                ->where('voted_at', now()->toDateString())
                ->exists();
            
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

        // Update festivity with locality_id
        $festivityData = $validated;
        $festivityData['locality_id'] = $locality->id;
        $festivityData['photos'] = $photos;
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
