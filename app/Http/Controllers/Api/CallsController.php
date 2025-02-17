<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Models\Call;
use App\Models\Patient;
use App\Http\Requests\StoreCallRequest;
use App\Http\Requests\UpdateCallRequest;
use App\Http\Resources\CallResource;
use Illuminate\Http\Request;

class CallsController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return CallResource::collection(Call::paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCallRequest $request)
    {
        $validated = $request->validated();

        // Verificar si es una llamada saliente y el usuario tiene permiso
        if ($validated['type'] === 'outgoing') {
            $patient = Patient::findOrFail($validated['patient_id']);
            if (!auth()->user()->zones->contains($patient->zone_id)) {
                return response()->json([
                    'message' => 'No tienes permiso para realizar llamadas salientes a pacientes fuera de tu zona'
                ], 403);
            }
        }

        // Asignar el operador actual
        $validated['operator_id'] = auth()->id();

        $call = Call::create($validated);

        return $this->sendResponse($call, 'Llamada creada ambèxit', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Call $call)
    {
        $call = Call::with(['patient', 'operator', 'category', 'alert'])->findOrFail($call->id);
        $this->authorize('view', $call);
        return $this->sendResponse(new CallResource($call), 'Llamada recuperada ambèxit', 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCallRequest $request, Call $call)
    {
        $this->authorize('update', $call);
        $validated = $request->validated();

        $call->update($validated);

        return $this->sendResponse(new CallResource($call), 'Llamada actualitzada ambèxit', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Call $call)
    {
        $call->delete();

        return $this->sendResponse(null, 'Llamada eliminada ambèxit', 204);
    }
}
