<?php

namespace App\Http\Controllers;

use App\Models\Adverticements;
use App\Models\Festive;
use Illuminate\Http\Request;

class AdvertisementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $advertisements = Adverticements::with('festive.town')->paginate(12);
        return view('advertisements.index', compact('advertisements'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $festives = Festive::with('town')->get();
        return view('advertisements.create', compact('festives'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'image_url' => 'required|url',
            'festive_id' => 'required|exists:festives,id',
        ]);

        Adverticements::create($request->all());

        return redirect()->route('advertisements.index')
            ->with('success', 'Anuncio creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Adverticements $advertisement)
    {
        $advertisement->load('festive.town');
        return view('advertisements.show', compact('advertisement'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Adverticements $advertisement)
    {
        $festives = Festive::with('town')->get();
        return view('advertisements.edit', compact('advertisement', 'festives'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Adverticements $advertisement)
    {
        $request->validate([
            'image_url' => 'required|url',
            'festive_id' => 'required|exists:festives,id',
        ]);

        $advertisement->update($request->all());

        return redirect()->route('advertisements.index')
            ->with('success', 'Anuncio actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Adverticements $advertisement)
    {
        $advertisement->delete();

        return redirect()->route('advertisements.index')
            ->with('success', 'Anuncio eliminado exitosamente.');
    }
}

