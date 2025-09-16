<?php

namespace App\Http\Controllers;

use App\Models\Adverticements;
use Illuminate\Http\Request;

class AdverticementsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'image_url' => 'required|url',
            'festive_id' => 'required|exists:festives,id',
        ]);
    
        Advertisement::create($data);
    
        return redirect()->route('advertisements.index')
                         ->with('success', 'Advertisement created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Adverticements $adverticements)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Adverticements $adverticements)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Adverticements $adverticements)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Adverticements $adverticements)
    {
        //
    }
}
