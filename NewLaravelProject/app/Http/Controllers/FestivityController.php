<?php

namespace App\Http\Controllers;

use App\Models\Festivity;
use App\Models\Locality;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class FestivityController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $festivities = Festivity::with('locality')->orderBy('start_date')->get();
        return view('festivities.index', compact('festivities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        try {
            $this->authorize('create', Festivity::class);
            
            return view('festivities.create');
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
            
            $validated = $request->validate([
                'locality_name' => 'required|string|max:255',
                'province' => 'required|string|in:' . implode(',', config('provinces.provinces')),
                'name' => 'required|string|max:255',
                'start_date' => 'required|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
                'description' => 'required|string',
                'photos' => 'nullable|array',
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

            // Create festivity with locality_id
            $festivityData = $validated;
            $festivityData['locality_id'] = $locality->id;
            unset($festivityData['locality_name']);
            
            Festivity::create($festivityData);

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
        
        return view('festivities.show', compact('festivity', 'userVotedToday', 'visitPointsEarned'));
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
            'photos' => 'nullable|array',
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

        // Update festivity with locality_id
        $festivityData = $validated;
        $festivityData['locality_id'] = $locality->id;
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
