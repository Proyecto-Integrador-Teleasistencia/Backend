<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\StoreAlertRequest;
use App\Http\Requests\UpdateAlertRequest;
use App\Models\Aviso;
use App\Http\Resources\AvisoResource;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class AlertsController extends BaseController
{
    /**
     * @OA\Get(
     *     path="/api/avisos",
     *     summary="Listar todos los avisos y alarmas con filtros",
     *     tags={"Avisos"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="type",
     *         in="query",
     *         description="Filtrar por tipo (alert/alarm)",
     *         required=false,
     *         @OA\Schema(type="string", enum={"alert", "alarm"})
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filtrar por estado",
     *         required=false,
     *         @OA\Schema(type="string", enum={"pending", "in_progress", "resolved"})
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de avisos y alarmas",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/AvisoResource")
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        try {
            $query = Aviso::with(['categoria']);

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
     *     summary="Crear un nuevo aviso o alarma",
     *     tags={"Avisos"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreAlertRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Aviso o alarma creada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/AvisoResource")
     *         )
     *     )
     * )
     */
    public function store(StoreAlertRequest $request)
    {
        $validated = $request->validated();
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
     *     path="/api/avisos/{id}",
     *     summary="Obtener detalles de un aviso o alarma",
     *     tags={"Avisos"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del aviso o alarma",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalles del aviso o alarma recuperados exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/AvisoResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Aviso o alarma no encontrado"
     *     )
     * )
     */
    public function show($id)
    {
        try {
            $alert = Aviso::with(['categoria', 'paciente', 'operador', 'zona'])->findOrFail($id);
            return $this->sendResponse(
                new AvisoResource($alert),
                'Avís/alarma recuperat ambèxit',
                200
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
     *     path="/api/avisos/{id}",
     *     summary="Actualizar un aviso o alarma existente",
     *     tags={"Avisos"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del aviso o alarma",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateAlertRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Aviso o alarma actualizado exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/AvisoResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Aviso o alarma no encontrado"
     *     )
     * )
     */
    public function update(UpdateAlertRequest $request, $id)
    {
        try {
            $alert = Aviso::findOrFail($id);
            $validated = $request->validated();
            
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
     *     path="/api/avisos/{id}",
     *     summary="Eliminar un aviso o alarma",
     *     tags={"Avisos"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del aviso o alarma",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Aviso o alarma eliminado exitosamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Aviso o alarma no encontrado"
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
