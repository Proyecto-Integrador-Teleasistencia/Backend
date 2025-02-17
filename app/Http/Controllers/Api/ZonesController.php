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
        return $this->sendResponse(Zone::withCount(['patients', 'operators'])
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

        return $this->sendResponse(new ZoneResource($zone), 'Zona creada ambèxit', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Zone $zone)
    {
        $zone = Zone::withCount(['patients', 'operators'])
            ->with('operators')
            ->findOrFail($zone->id);

        return $this->sendResponse(new ZoneResource($zone), 'Zona obtenida ambèxit', 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateZoneRequest $request, Zone $zone)
    {
        $zone = Zone::findOrFail($zone->id);

        $zone->update($validated);

        return $this->sendResponse(new ZoneResource($zone), 'Zona actualizada ambèxit', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Zone $zone)
    {
        $zone = Zone::findOrFail($zone->id);

        // Check if zone has patients
        if ($zone->patients()->exists()) {
            return $this->sendResponse(null, 'No se puede eliminar la zona con pacientes asociados', 422);
        }

        $zone->delete();

        return $this->sendResponse(null, 'Zona eliminada ambèxit', 204);
    }

    /**
     * Assign operators to a zone
     */
    public function assignOperators(Request $request, Zone $zone)
    {
        $zone = Zone::findOrFail($zone->id);

        $validated = $request->validate([
            'operators' => 'required|array',
            'operators.*' => 'required|exists:users,id'
        ]);

        $zone->operators()->sync($validated['operators']);

        return $this->sendResponse(new ZoneResource($zone->load('operators')), 'Operadores asignados a la zona ambèxit', 200);
    }

    /**
     * Remove operators from a zone
     */
    public function removeOperators(Request $request, Zone $zone)
    {
        $zone = Zone::findOrFail($zone->id);

        $validated = $request->validate([
            'operators' => 'required|array',
            'operators.*' => 'required|exists:users,id'
        ]);

        $zone->operators()->detach($validated['operators']);

        return $this->sendResponse(new ZoneResource($zone->load('operators')), 'Operadores eliminados de la zona ambèxit', 200);
    }
}
