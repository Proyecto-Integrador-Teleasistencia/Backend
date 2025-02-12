<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Call;
use App\Http\Requests\StoreCallRequest;
use App\Http\Requests\UpdateCallRequest;
use Illuminate\Http\Request;

class CallsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Call::class);

        $query = Call::with(['patient', 'operator', 'category', 'alert']);

        // Si es operador, filtrar solo las llamadas de sus zonas
        if (auth()->user()->role === 'operator') {
            $zoneIds = auth()->user()->zones->pluck('id');
            $query->whereHas('patient', function ($q) use ($zoneIds) {
                $q->whereIn('zone_id', $zoneIds);
            });
        }

        return response()->json($query->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCallRequest $request)
    {
        $this->authorize('create', Call::class);
        
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

        return response()->json($call, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $call = Call::with(['patient', 'operator', 'category', 'alert'])->findOrFail($id);
        $this->authorize('view', $call);
        return response()->json($call);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCallRequest $request, string $id)
    {
        $call = Call::findOrFail($id);
        $this->authorize('update', $call);
        $validated = $request->validated();
            'datetime' => 'sometimes|required|date',
            'description' => 'nullable|string',
            'type' => 'sometimes|required|string|in:outgoing,incoming',
            'scheduled' => 'sometimes|required|boolean',
            'operator_id' => 'sometimes|required|exists:users,id',
            'patient_id' => 'sometimes|required|exists:patients,id',
            'category_id' => 'sometimes|required|exists:categories,id',
            'alert_id' => 'nullable|exists:alerts,id',
        ]);

        $call->update($validated);

        return response()->json($call);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $call = Call::findOrFail($id);
        $this->authorize('delete', $call);
        $call->delete();

        return response()->json(null, 204);
    }
}
