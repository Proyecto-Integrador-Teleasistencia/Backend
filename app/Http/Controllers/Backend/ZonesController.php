<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Zone;
use Illuminate\Http\Request;

class ZonesController extends Controller
{
    /**
     * Display a listing of the zones.
     */
    public function index()
    {
        $zones = Zone::paginate(10);
        return view('backend.zones.index', compact('zones'));
    }

    /**
     * Show the form for creating a new zone.
     */
    public function create()
    {
        return view('backend.zones.create');
    }

    /**
     * Store a newly created zone in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Zone::create($validated);

        return redirect()->route('backend.zones.index')
            ->with('success', 'Zona creada correctamente');
    }

    /**
     * Show the form for editing the specified zone.
     */
    public function edit(Zone $zone)
    {
        return view('backend.zones.edit', compact('zone'));
    }

    /**
     * Update the specified zone in storage.
     */
    public function update(Request $request, Zone $zone)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $zone->update($validated);

        return redirect()->route('backend.zones.index')
            ->with('success', 'Zona actualizada correctamente');
    }

    /**
     * Remove the specified zone from storage.
     */
    public function destroy(Zone $zone)
    {
        $zone->delete();

        return redirect()->route('backend.zones.index')
            ->with('success', 'Zona eliminada correctamente');
    }
}
