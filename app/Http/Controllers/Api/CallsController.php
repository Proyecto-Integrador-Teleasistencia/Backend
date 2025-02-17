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
     * @OA\Get(
     *     path="/api/calls",
     *     summary="List calls with filters",
     *     tags={"Calls"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="date",
     *         in="query",
     *         description="Filter by date (Y-m-d)",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="type",
     *         in="query",
     *         description="Filter by type (incoming/outgoing)",
     *         required=false,
     *         @OA\Schema(type="string", enum={"incoming", "outgoing"})
     *     ),
     *     @OA\Parameter(
     *         name="zone_id",
     *         in="query",
     *         description="Filter by zone ID",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of calls"
     *     )
     * )
     */
    public function index(Request $request)
    {
        $query = Call::query()->with(['patient', 'operator', 'category', 'alert']);

        // Filtro por fecha
        if ($request->has('date')) {
            $query->whereDate('created_at', $request->date);
        }

        // Filtro por tipo
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        // Filtro por zona
        if ($request->has('zone_id')) {
            $query->whereHas('patient', function($q) use ($request) {
                $q->where('zone_id', $request->zone_id);
            });
        }

        return $this->sendResponse(
            CallResource::collection($query->paginate(10)),
            'Cridades recuperades amb èxit'
        );
    }

    /**
     * @OA\Get(
     *     path="/api/patients/{patient_id}/calls",
     *     summary="List calls of a patient",
     *     tags={"Calls"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="patient_id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of patient's calls"
     *     )
     * )
     */
    public function getPatientCalls($patientId)
    {
        $patient = Patient::findOrFail($patientId);
        $calls = $patient->calls()
            ->with(['operator', 'category', 'alert'])
            ->paginate(10);
            
        return $this->sendResponse(
            CallResource::collection($calls),
            'Cridades del pacient recuperades amb èxit'
        );
    }

    /**
     * @OA\Post(
     *     path="/api/calls",
     *     summary="Create a new call",
     *     tags={"Calls"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=201,
     *         description="Call created successfully"
     *     )
     * )
     */
    public function store(StoreCallRequest $request)
    {
        $validated = $request->validated();

        // Verificar si es una llamada saliente y el usuario tiene permiso
        if ($validated['type'] === 'outgoing') {
            $patient = Patient::findOrFail($validated['patient_id']);
            if (!auth()->user()->zones->contains($patient->zone_id)) {
                return $this->sendError(
                    'No tens permís per realitzar cridades sortints a pacients fora de la teva zona',
                    [], 403
                );
            }
        }

        // Asignar el operador actual
        $validated['operator_id'] = auth()->id();

        $call = Call::create($validated);

        return $this->sendResponse(
            new CallResource($call),
            'Crida creada amb èxit',
            201
        );
    }

    /**
     * @OA\Get(
     *     path="/api/calls/{id}",
     *     summary="Get call details",
     *     tags={"Calls"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Call details retrieved successfully"
     *     )
     * )
     */
    public function show(Call $call)
    {
        $call = Call::with(['patient', 'operator', 'category', 'alert'])->findOrFail($call->id);
        $this->authorize('view', $call);
        
        return $this->sendResponse(
            new CallResource($call),
            'Crida recuperada amb èxit'
        );
    }

    /**
     * @OA\Put(
     *     path="/api/calls/{id}",
     *     summary="Update a call",
     *     tags={"Calls"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Call updated successfully"
     *     )
     * )
     */
    public function update(UpdateCallRequest $request, Call $call)
    {
        $this->authorize('update', $call);
        $validated = $request->validated();
        $call->update($validated);
        
        return $this->sendResponse(
            new CallResource($call),
            'Crida actualitzada amb èxit'
        );
    }

    /**
     * @OA\Delete(
     *     path="/api/calls/{id}",
     *     summary="Delete a call",
     *     tags={"Calls"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Call deleted successfully"
     *     )
     * )
     */
    public function destroy(Call $call)
    {
        $this->authorize('delete', $call);
        $call->delete();
        
        return $this->sendResponse(
            null,
            'Crida eliminada amb èxit'
        );
    }
}
