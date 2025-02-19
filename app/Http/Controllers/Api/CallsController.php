<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Models\Llamada;
use App\Models\Paciente;
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
        try {
            $query = Llamada::query()->with(['paciente', 'operador', 'categoria', 'subcategoria']);

            // Filtro por fecha
            if ($request->has('fecha_hora')) {
                $query->whereDate('fecha_hora', $request->fecha_hora);
            }

            // Filtro por tipo
            if ($request->has('tipo_llamada')) {
                $query->where('tipo_llamada', $request->tipo_llamada);
            }

            // Filtro por zona
            if ($request->has('zona_id')) {
                $query->whereHas('paciente', function($q) use ($request) {
                    $q->where('zona_id', $request->zona_id);
                });
            }

            $calls = $query->paginate(10);
            
            if ($calls->isEmpty()) {
                return $this->sendResponse(
                    [],
                    'No hi ha crides disponibles'
                );
            }

            return $this->sendResponse(
                CallResource::collection($calls),
                'Crides recuperades amb èxit'
            );
        } catch (\Exception $e) {
            return $this->sendError(
                'Error al recuperar les crides',
                [], 500
            );
        }
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
        if ($validated['tipo_llamada'] === 'saliente') {
            $patient = Paciente::findOrFail($validated['paciente_id']);
            // Los administradores pueden hacer llamadas a cualquier zona
            if (auth()->user()->role !== 'admin') {
                if (!auth()->user()->zonas->contains($patient->zona_id)) {
                    return $this->sendError(
                        'No tens permís per realitzar cridades sortints a pacients fora de la teva zona',
                        [], 403
                    );
                }
            }
        }

        // Asignar el operador actual
        $validated['operador_id'] = auth()->id();

        $call = Llamada::create($validated);

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
    public function show($id)
    {
        try {
            $call = Llamada::with(['paciente', 'operador', 'categoria', 'subcategoria'])->findOrFail($id);
            // $this->authorize('view', $call);
            
            return $this->sendResponse(
                new CallResource($call),
                'Crida recuperada ambèxit'
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->sendError(
                'No s\'ha trobat la crida',
                [], 404
            );
        }
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
    public function update(UpdateCallRequest $request, $id)
    {
        try {
            $call = Llamada::findOrFail($id);
            // $this->authorize('update', $call);
            $validated = $request->validated();
            $call->update($validated);
            
            return $this->sendResponse(
                new CallResource($call),
                'Crida actualitzada amb èxit'
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->sendError(
                'No s\'ha trobat la crida',
                [], 404
            );
        }
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
    public function destroy($id)
    {
        try {
            $call = Llamada::findOrFail($id);
            // $this->authorize('delete', $call);
            $call->delete();
            
            return $this->sendResponse(
                null,
                'Crida eliminada amb èxit'
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->sendError(
                'No s\'ha trobat la crida',
                [], 404
            );
        }
    }
}
