<?php

namespace App\Http\Controllers;

use App\Models\Festive;
use App\Models\Town;
use Illuminate\Http\Request;

class FestiveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $festives = Festive::with(['town', 'advertisements'])->paginate(12);
        return view('festives.index', compact('festives'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $towns = Town::all();
        return view('festives.create', compact('towns'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'town_id' => 'required|exists:towns,id',
        ]);

        Festive::create($request->all());

        return redirect()->route('festives.index')
            ->with('success', 'Festividad creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Festive $festive)
    {
        $festive->load(['town', 'advertisements']);
        return view('festives.show', compact('festive'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Festive $festive)
    {
        $towns = Town::all();
        return view('festives.edit', compact('festive', 'towns'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Festive $festive)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'town_id' => 'required|exists:towns,id',
        ]);

        $festive->update($request->all());

        return redirect()->route('festives.index')
            ->with('success', 'Festividad actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Festive $festive)
    {
        $festive->delete();

        return redirect()->route('festives.index')
            ->with('success', 'Festividad eliminada exitosamente.');
    }
}