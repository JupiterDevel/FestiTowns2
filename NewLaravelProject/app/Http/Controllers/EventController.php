<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Festivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{

    /**
     * Display a listing of events for a specific festivity.
     */
    public function index(Festivity $festivity)
    {
        $events = $festivity->events()->paginate(10);
        
        return view('events.index', compact('festivity', 'events'));
    }

    /**
     * Show the form for creating a new event.
     */
    public function create(Festivity $festivity)
    {
        $user = Auth::user();
        
        // Los usuarios Visitor no pueden crear eventos
        if ($user && $user->isVisitor()) {
            abort(403, 'Los usuarios con rol Visitor no pueden crear eventos.');
        }
        
        // Los usuarios TownHall solo pueden crear eventos en festividades de su localidad
        if ($user && $user->isTownHall()) {
            if (!$user->locality_id || $festivity->locality_id !== $user->locality_id) {
                abort(403, 'Solo puedes crear eventos en festividades de tu localidad.');
            }
        }
        
        return view('events.create', compact('festivity'));
    }

    /**
     * Store a newly created event in storage.
     */
    public function store(Request $request, Festivity $festivity)
    {
        $user = Auth::user();
        
        // Los usuarios Visitor no pueden crear eventos
        if ($user && $user->isVisitor()) {
            abort(403, 'Los usuarios con rol Visitor no pueden crear eventos.');
        }
        
        // Los usuarios TownHall solo pueden crear eventos en festividades de su localidad
        if ($user && $user->isTownHall()) {
            if (!$user->locality_id || $festivity->locality_id !== $user->locality_id) {
                abort(403, 'Solo puedes crear eventos en festividades de tu localidad.');
            }
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date|after_or_equal:start_time',
        ]);

        $festivity->events()->create($request->all());

        return redirect()->route('festivities.show', $festivity)
            ->with('success', 'Evento creado exitosamente.');
    }

    /**
     * Display the specified event.
     */
    public function show(Festivity $festivity, Event $event)
    {
        return view('events.show', compact('festivity', 'event'));
    }

    /**
     * Show the form for editing the specified event.
     */
    public function edit(Festivity $festivity, Event $event)
    {
        return view('events.edit', compact('festivity', 'event'));
    }

    /**
     * Update the specified event in storage.
     */
    public function update(Request $request, Festivity $festivity, Event $event)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date|after_or_equal:start_time',
        ]);

        $event->update($request->all());

        return redirect()->route('festivities.show', $festivity)
            ->with('success', 'Evento actualizado exitosamente.');
    }

    /**
     * Remove the specified event from storage.
     */
    public function destroy(Festivity $festivity, Event $event)
    {
        $event->delete();

        return redirect()->route('festivities.show', $festivity)
            ->with('success', 'Evento eliminado exitosamente.');
    }
}
