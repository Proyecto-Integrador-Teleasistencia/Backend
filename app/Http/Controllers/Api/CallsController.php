<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Models\Llamada;
use App\Models\Paciente;
use App\Http\Requests\StoreCallRequest;
use App\Http\Requests\UpdateCallRequest;
use App\Http\Resources\CallResource;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class CallsController extends BaseController
{
    /**
     * @OA\Get(
     *     path="/api/llamadas",
     *     summary="List calls with filters",
     *     tags={"Llamadas"},
     *     security={{"bearerAuth":{}}},
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
     *         description="Filter by call type (incoming/outgoing)",
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
     *         description="List of calls retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/CallResource")
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        try {
            $query = Llamada::query()
                ->with(['paciente', 'operador', 'categoria', 'subcategoria'])
                ->orderBy('fecha_hora', 'desc');

            if ($request->has('date')) {
                $query->whereDate('fecha_hora', $request->input('date'));
            }

            if ($request->has('type')) {
                $query->where('tipo_llamada', $request->input('type'));
            }

            if ($request->has('zone_id')) {
                $query->whereHas('paciente', function ($q) use ($request) {
                    $q->where('zona_id', $request->input('zone_id'));
                });
            }

            if ($request->has('estado')) {
                $query->where('estado', $request->input('estado'));
            }

            $calls = $query->get();

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

    
    public function getPatientCalls($patientId)
    {
        try {
            $patient = Paciente::findOrFail($patientId);
            $calls = $patient->llamadas()
                ->with(['operador', 'categoria', 'subcategoria', 'paciente'])
                ->get();
                
            return $this->sendResponse(
                CallResource::collection($calls),
                'Cridades del pacient recuperades ambèxit'
            );
        } catch (\Exception $e) {
            return $this->sendError('Error al recuperar les crides del pacient', [], 500);
        }
    }

    public function getOperatorCalls($operatorId)
    {
        try {
            $calls = Llamada::where('operador_id', $operatorId)
                ->with(['paciente', 'categoria', 'subcategoria', 'operador'])
                ->get();

            return $this->sendResponse(
                CallResource::collection($calls),
                'Cridades del teleoperador recuperades amb èxit'
            );
        } catch (\Exception $e) {
            return $this->sendError('Error al recuperar les crides del teleoperador', [], 500);
        }
    }

    public function getCallsByType($type)
    {
        try {
            $calls = Llamada::where('tipo_llamada', $type)
                ->with(['paciente', 'operador', 'categoria', 'subcategoria'])
                ->get();

            return $this->sendResponse(
                CallResource::collection($calls),
                'Cridades del tipus ' . $type . ' recuperades ambèxit'
            );
        } catch (\Exception $e) {
            return $this->sendError('Error al recuperar les crides per tipus', [], 500);
        }
    }

    public function getCallsByTypeAndPatient($type, $patientId)
    {
        try {
            $patient = Paciente::findOrFail($patientId);
            $calls = $patient->llamadas()
                ->where('tipo_llamada', $type)
                ->with(['operador', 'categoria', 'subcategoria', 'paciente'])
                ->get();
            return $this->sendResponse(
                CallResource::collection($calls),
                'Cridades del pacient per tipus recuperades amb èxit'
            );
        } catch (\Exception $e) {
            return $this->sendError('Error al recuperar les crides del pacient per tipus', [$e->getMessage()], 500);
        }
    }

    public function getCallsByPatientAndType($patientId, $type)
    {
        try {
            $patient = Paciente::findOrFail($patientId);
            $calls = $patient->llamadas()
                ->where('tipo_llamada', $type)
                ->with(['operador', 'categoria', 'subcategoria', 'paciente'])
                ->get();
            return $this->sendResponse(
                CallResource::collection($calls),
                'Cridades del pacient per tipus recuperades amb èxit'
            );
        } catch (\Exception $e) {
            return $this->sendError('Error al recuperar les crides del pacient per tipus', [$e->getMessage()], 500);
        }
    }

    public function getCallsByOperatorAndType($type, $operatorId)
    {
        try {
            $calls = Llamada::where('operador_id', $operatorId)
                ->where('tipo_llamada', $type)
                ->with(['paciente', 'categoria', 'subcategoria', 'operador'])
                ->get();

            return $this->sendResponse(
                CallResource::collection($calls),
                'Cridades del teleoperador per tipus recuperades amb èxit'
            );
        } catch (\Exception $e) {
            return $this->sendError('Error al recuperar les crides del teleoperador per tipus', [], 500);
        }
    }

    public function getCallsByOperatorPatientAndType($type, $operatorId, $patientId)
    {
        try {
            $calls = Llamada::where('operador_id', $operatorId)
                ->where('paciente_id', $patientId)
                ->where('tipo_llamada', $type)
                ->with(['paciente', 'categoria', 'subcategoria', 'operador'])
                ->get();

            return $this->sendResponse(
                CallResource::collection($calls),
                'Cridades filtrades per teleoperador, pacient i tipus recuperades amb èxit'
            );
        } catch (\Exception $e) {
            return $this->sendError('Error al recuperar les crides filtrades', [], 500);
        }
    }

    public function getCallsByOperatorPatient($operatorId, $patientId) {
        try {
            $calls = Llamada::where('operador_id', $operatorId)
                ->where('paciente_id', $patientId)
                ->with(['paciente', 'categoria', 'subcategoria', 'operador'])
                ->get();

            return $this->sendResponse(
                CallResource::collection($calls),
                'Cridades filtrades per teleoperador i pacient recuperades ambèxit'
            );
        } catch (\Exception $e) {
            return $this->sendError('Error al recuperar les crides filtrades', [], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/llamadas",
     *     summary="Crear una nueva llamada",
     *     tags={"Llamadas"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreCallRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Llamada creada con éxito",
     *         @OA\JsonContent(ref="#/components/schemas/CallResource")
     *     )
     * )
     */
    public function store(StoreCallRequest $request)
    {
        $validated = $request->validated();

        // Verificar si es una llamada saliente y el usuario tiene permiso
        // if ($validated['tipo_llamada'] === 'saliente') {
        //     $patient = Paciente::findOrFail($validated['paciente_id']);
        //     $this->authorize('makeOutgoingCall', $patient);
        // }

        // Asignar el operador actual
        $validated['operador_id'] = auth()->id();

        $call = Llamada::create($validated);

        return $this->sendResponse(
            new CallResource($call),
            'Crida creada ambèxit',
            201
        );
    }

    /**
     * @OA\Get(
     *     path="/api/llamadas/{id}",
     *     summary="Obtenir una crida per ID",
     *     tags={"Llamadas"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="date",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string", format="date"),
     *         description="Filter calls by date"
     *     ),
     *     @OA\Parameter(
     *         name="type",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string"),
     *         description="Filter calls by type"
     *     ),
     *     @OA\Parameter(
     *         name="zone_id",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer"),
     *         description="Filter calls by zone ID"
     *     ),
     *     @OA\Parameter(
     *         name="estado",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string"),
     *         description="Filter calls by state"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Call retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/CallResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Call not found"
     *     )
     * )
     */
    public function show($id, Request $request)
    {
        try {
            $call = Llamada::with(['paciente', 'operador', 'categoria', 'subcategoria'])->findOrFail($id);

            if ($request->has('date')) {
                $call->whereDate('fecha_hora', $request->input('date'));
            }

            if ($request->has('type')) {
                $call->where('tipo_llamada', $request->input('type'));
            }

            if ($request->has('zone_id')) {
                $call->whereHas('paciente', function ($q) use ($request) {
                    $q->where('zona_id', $request->input('zone_id'));
                });
            }

            if ($request->has('estado')) {
                $call->where('estado', $request->input('estado'));
            }
            
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
     *     path="/api/llamadas/{id}",
     *     summary="Update a call",
     *     tags={"Llamadas"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateCallRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Call updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/CallResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Call not found"
     *     )
     * )
     */
    public function update(UpdateCallRequest $request, $id)
    {
        try {
            $call = Llamada::findOrFail($id);
            $this->authorize('update', $call);
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
     *     path="/api/llamadas/{id}",
     *     summary="Eliminar una llamada",
     *     tags={"Llamadas"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Crida eliminada ambèxit"
     *     )
     * )
     */
    public function destroy($id)
    {
        try {
            $call = Llamada::findOrFail($id);
            $this->authorize('delete', $call);
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
