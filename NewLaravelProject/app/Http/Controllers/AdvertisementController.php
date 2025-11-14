<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\Festivity;
use App\Models\Locality;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdvertisementController extends Controller
{
    use AuthorizesRequests;

    private const ALLOWED_SORTS = ['name', 'priority', 'active', 'created_at'];

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Advertisement::class);

        $search = $request->string('search')->toString();
        $sort = in_array($request->get('sort'), self::ALLOWED_SORTS, true) ? $request->get('sort') : 'created_at';
        $direction = $request->get('direction') === 'asc' ? 'asc' : 'desc';

        $advertisements = Advertisement::with(['festivity', 'locality'])
            ->premium()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('name', 'like', "%{$search}%")
                        ->orWhere('url', 'like', "%{$search}%");
                });
            })
            ->orderBy($sort, $direction)
            ->paginate(12)
            ->withQueryString();

        return view('ads.admin.index', compact('advertisements', 'search', 'sort', 'direction'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Advertisement::class);

        $festivities = Festivity::with('locality')->orderBy('name')->get();
        $localities = Locality::orderBy('name')->get();

        return view('ads.admin.create', compact('festivities', 'localities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Advertisement::class);

        $data = $this->validateData($request);
        $data['premium'] = true;
        $data['active'] = $request->boolean('active', true);
        $data['image'] = $this->storeImage($request);

        Advertisement::create($data);

        return redirect()->route('advertisements.index')->with('success', 'Anuncio premium creado correctamente.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Advertisement $advertisement)
    {
        $this->authorize('update', $advertisement);
        $this->ensurePremium($advertisement);

        $festivities = Festivity::with('locality')->orderBy('name')->get();
        $localities = Locality::orderBy('name')->get();

        return view('ads.admin.edit', compact('advertisement', 'festivities', 'localities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Advertisement $advertisement)
    {
        $this->authorize('update', $advertisement);
        $this->ensurePremium($advertisement);

        $data = $this->validateData($request, $advertisement->id);
        $data['premium'] = true;
        $data['active'] = $request->boolean('active', true);

        if ($request->hasFile('image')) {
            $this->deleteImage($advertisement->image);
            $data['image'] = $this->storeImage($request);
        }

        $advertisement->update($data);

        return redirect()->route('advertisements.index')->with('success', 'Anuncio premium actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Advertisement $advertisement)
    {
        $this->authorize('delete', $advertisement);
        $this->ensurePremium($advertisement);

        $this->deleteImage($advertisement->image);
        $advertisement->delete();

        return redirect()->route('advertisements.index')->with('success', 'Anuncio eliminado correctamente.');
    }

    public function toggle(Request $request, Advertisement $advertisement)
    {
        $this->authorize('toggle', $advertisement);
        $this->ensurePremium($advertisement);

        $validated = $request->validate([
            'active' => ['required', 'boolean'],
        ]);

        $advertisement->update(['active' => $validated['active']]);

        return back()->with('success', 'Estado del anuncio actualizado.');
    }

    protected function validateData(Request $request, ?int $ignoreId = null): array
    {
        $festivityRule = ['required', 'exists:festivities,id'];
        $imageRule = $ignoreId ? ['nullable'] : ['required'];

        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'url' => ['nullable', 'url', 'max:255'],
            'festivity_id' => $festivityRule,
            'locality_id' => ['nullable', 'exists:localities,id'],
            'priority' => ['required', 'in:principal,secondary'],
            'active' => ['sometimes', 'boolean'],
            'image' => array_merge($imageRule, ['image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120']),
        ]);
    }

    protected function storeImage(Request $request): string
    {
        $path = $request->file('image')->store('ads', 'public');

        return '/storage/' . $path;
    }

    protected function deleteImage(?string $path): void
    {
        if (!$path || !str_starts_with($path, '/storage/')) {
            return;
        }

        $relativePath = str_replace('/storage/', '', $path);

        if (Storage::disk('public')->exists($relativePath)) {
            Storage::disk('public')->delete($relativePath);
        }
    }

    protected function ensurePremium(Advertisement $advertisement): void
    {
        if (!$advertisement->premium) {
            abort(404);
        }
    }
}
