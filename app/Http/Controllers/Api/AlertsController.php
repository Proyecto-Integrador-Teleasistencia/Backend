<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\StoreAlertRequest;
use App\Http\Requests\UpdateAlertRequest;
use App\Models\Aviso;
use App\Http\Resources\AvisoResource;
use Illuminate\Http\Request;

class AlertsController extends BaseController
{
    /**
     * @OA\Get(
     *     path="/api/alerts",
     *     summary="List alerts and alarms",
     *     tags={"Alerts"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="type",
     *         in="query",
     *         description="Filter by type (alert/alarm)",
     *         required=false,
     *         @OA\Schema(type="string", enum={"alert", "alarm"})
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filter by status",
     *         required=false,
     *         @OA\Schema(type="string", enum={"pending", "in_progress", "resolved"})
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of alerts and alarms",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Alert")
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        try {
            $query = Aviso::with(['categoria']);

            // Filtrar por periocidad
            if ($request->has('periocidad')) {
                $query->where('periocidad', $request->periocidad);
            }

            $alerts = $query->latest()->get();

            if ($alerts->isEmpty()) {
                return $this->sendResponse(
                    [],
                    'No hi ha avisos ni alarmes disponibles'
                );
            }

            return $this->sendResponse(
                AvisoResource::collection($alerts),
                'Avisos i alarmes recuperats amb èxit'
            );
        } catch (\Exception $e) {
            return $this->sendError(
                'Error al recuperar els avisos i alarmes',
                [], 500
            );
        }
    }

    /**
     * @OA\Post(
     *     path="/api/avisos",
     *     summary="Create a new alert or alarm",
     *     tags={"Alerts"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/AlertRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Alert created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/Alert")
     *         )
     *     )
     * )
     */
    public function store(StoreAlertRequest $request)
    {
        $validated = $request->validated();
        // Asignar el operador actual si no se especifica
        if (!isset($validated['operador_id'])) {
            $validated['operador_id'] = auth()->id();
        }
        
        $alert = Aviso::create($validated);
        
        return $this->sendResponse(
            new AvisoResource($alert),
            'Avís/alarma creat amb èxit',
            201
        );
    }

    /**
     * @OA\Get(
     *     path="/api/alerts/{id}",
     *     summary="Get alert/alarm details",
     *     tags={"Alerts"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Alert details retrieved successfully"
     *     )
     * )
     */
    public function show($id)
    {
        try {
            $alert = Aviso::with(['categoria', 'paciente', 'operador'])->findOrFail($id);
            return $this->sendResponse(
                new AvisoResource($alert),
                'Avís/alarma recuperat ambèxit'
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->sendError(
                'No s\'ha trobat l\'avís/alarma',
                [], 404
            );
        }
    }

    /**
     * @OA\Put(
     *     path="/api/alerts/{id}",
     *     summary="Update an alert/alarm",
     *     tags={"Alerts"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Alert updated successfully"
     *     )
     * )
     */
    public function update(UpdateAlertRequest $request, $id)
    {
        try {
            $alert = Aviso::findOrFail($id);
            $validated = $request->validated();
            
            // Si se está cambiando el estado a "resolved", registrar la fecha de resolución
            if (isset($validated['status']) && $validated['status'] === 'resolved' && $alert->status !== 'resolved') {
                $validated['resolved_at'] = now();
            }
            
            $alert->update($validated);
            
            return $this->sendResponse(
                new AvisoResource($alert),
                'Avís/alarma actualitzat amb èxit'
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->sendError(
                'No s\'ha trobat l\'avís/alarma',
                [], 404
            );
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/alerts/{id}",
     *     summary="Delete an alert/alarm",
     *     tags={"Alerts"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Alert deleted successfully"
     *     )
     * )
     */
    public function destroy($id)
    {
        try {
            $alert = Aviso::findOrFail($id);
            $alert->delete();
            return $this->sendResponse(
                null,
                'Avís/alarma eliminat amb èxit'
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->sendError(
                'No s\'ha trobat l\'avís/alarma',
                [], 404
            );
        }
    }
}
