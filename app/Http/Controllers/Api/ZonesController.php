<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Models\Zone;
use App\Http\Requests\StoreZoneRequest;
use App\Http\Requests\UpdateZoneRequest;
use Illuminate\Http\Request;

class ZonesController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Zone::withCount(['patients', 'operators'])
            ->with('operators')
            ->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreZoneRequest $request)
    {
        $validated = $request->validated();

        $zone = Zone::create($validated);

        return response()->json($zone, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $zone = Zone::withCount(['patients', 'operators'])
            ->with('operators')
            ->findOrFail($id);
        return response()->json($zone);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateZoneRequest $request, string $id)
    {
        $zone = Zone::findOrFail($id);

        $zone->update($validated);

        return response()->json($zone);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $zone = Zone::findOrFail($id);
        
        // Check if zone has patients
        if ($zone->patients()->exists()) {
            return response()->json([
                'message' => 'Cannot delete zone with associated patients'
            ], 422);
        }

        $zone->delete();

        return response()->json(null, 204);
    }

    /**
     * Assign operators to a zone
     */
    public function assignOperators(Request $request, string $id)
    {
        $zone = Zone::findOrFail($id);

        $validated = $request->validate([
            'operators' => 'required|array',
            'operators.*' => 'required|exists:users,id'
        ]);

        $zone->operators()->sync($validated['operators']);

        return response()->json($zone->load('operators'));
    }

    /**
     * Remove operators from a zone
     */
    public function removeOperators(Request $request, string $id)
    {
        $zone = Zone::findOrFail($id);

        $validated = $request->validate([
            'operators' => 'required|array',
            'operators.*' => 'required|exists:users,id'
        ]);

        $zone->operators()->detach($validated['operators']);

        return response()->json($zone->load('operators'));
    }
}
