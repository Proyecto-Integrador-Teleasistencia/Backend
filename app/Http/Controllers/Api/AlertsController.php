<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\StoreAlertRequest;
use App\Http\Requests\UpdateAlertRequest;
use App\Models\Alert;
use App\Http\Resources\AlertResource;
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
        $query = Alert::with(['category', 'patient', 'operator']);

        // Filtrar por tipo
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        // Filtrar por estado
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $alerts = $query->latest()->paginate(10);
        return $this->sendResponse(
            AlertResource::collection($alerts),
            'Avisos i alarmes recuperats amb èxit'
        );
    }

    /**
     * @OA\Post(
     *     path="/api/alerts",
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
        if (!isset($validated['operator_id'])) {
            $validated['operator_id'] = auth()->id();
        }
        
        $alert = Alert::create($validated);
        
        return $this->sendResponse(
            new AlertResource($alert),
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
    public function show(Alert $alert)
    {
        $alert->load(['category', 'patient', 'operator']);
        return $this->sendResponse(
            new AlertResource($alert),
            'Avís/alarma recuperat amb èxit'
        );
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
    public function update(UpdateAlertRequest $request, Alert $alert)
    {
        $validated = $request->validated();
        
        // Si se está cambiando el estado a "resolved", registrar la fecha de resolución
        if (isset($validated['status']) && $validated['status'] === 'resolved' && $alert->status !== 'resolved') {
            $validated['resolved_at'] = now();
        }
        
        $alert->update($validated);
        
        return $this->sendResponse(
            new AlertResource($alert),
            'Avís/alarma actualitzat amb èxit'
        );
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
    public function destroy(Alert $alert)
    {
        $alert->delete();
        return $this->sendResponse(
            null,
            'Avís/alarma eliminat amb èxit'
        );
    }
}
