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
    public function index()
    {
        $localities = Locality::with('festivities')->get();
        
        // SEO Meta Tags
        $meta = SeoService::generateMetaTags([
            'title' => 'Localidades de España - Festividades y Turismo | FestiTowns',
            'description' => 'Explora las localidades españolas y descubre sus festividades tradicionales, lugares de interés y monumentos. Planifica tu visita y conoce la cultura de España.',
            'keywords' => 'localidades españa, turismo españa, pueblos españa, ciudades españa, festividades por localidad',
            'url' => route('localities.index'),
        ]);
        
        return view('localities.index', compact('localities', 'meta'));
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
