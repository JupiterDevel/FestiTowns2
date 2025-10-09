<?php

namespace App\Http\Controllers;

use App\Models\Locality;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class LocalityController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $localities = Locality::with('festivities')->get();
        return view('localities.index', compact('localities'));
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
                'description' => 'required|string',
                'places_of_interest' => 'required|string',
                'monuments' => 'required|string',
                'photos' => 'nullable|array',
            ]);

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
        return view('localities.show', compact('locality'));
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
            'description' => 'required|string',
            'places_of_interest' => 'required|string',
            'monuments' => 'required|string',
            'photos' => 'nullable|array',
        ]);

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
