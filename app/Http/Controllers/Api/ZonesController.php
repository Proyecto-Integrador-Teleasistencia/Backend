<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Models\Zona;
use App\Http\Resources\ZoneResource;
use App\Http\Resources\PatientResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Http\Requests\StoreZoneRequest;
use App\Http\Requests\UpdateZoneRequest;
use OpenApi\Annotations as OA;

class ZonesController extends BaseController
{
    /**
     * @OA\Post(
     *     path="/api/zonas",
     *     summary="Crear una nueva zona",
     *     tags={"Zonas"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreZoneRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Zona creada con éxito",
     *         @OA\JsonContent(ref="#/components/schemas/ZoneResource")
     *     )
     * )
     */
    public function store(StoreZoneRequest $request)
    {
        try {
            $validated = $request->validated();
            $zona = Zona::create($validated);

            return $this->sendResponse(
                new ZoneResource($zona),
                'Zona creada amb èxit',
                201
            );
        } catch (\Exception $e) {
            return $this->sendError('Error al crear la zona', $e->getMessage());
        }
    }

    /**
     * @OA\Put(
     *     path="/api/zonas/{zona}",
     *     summary="Actualizar una zona existente",
     *     tags={"Zonas"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="zona",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateZoneRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Zona actualizada con éxito",
     *         @OA\JsonContent(ref="#/components/schemas/ZoneResource")
     *     )
     * )
     */
    public function update(UpdateZoneRequest $request, Zona $zona)
    {
        try {
            $validated = $request->validated();
            $zona->update($validated);

            return $this->sendResponse(
                new ZoneResource($zona),
                'Zona actualitzada amb èxit'
            );
        } catch (\Exception $e) {
            return $this->sendError('Error al actualitzar la zona', $e->getMessage());
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/zonas/{zona}",
     *     summary="Eliminar una zona",
     *     tags={"Zonas"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="zona",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Zona eliminada con éxito"
     *     )
     * )
     */
    public function destroy(Zona $zona)
    {
        try {
            $zona->delete();

            return $this->sendResponse(
                [],
                'Zona eliminada amb èxit'
            );
        } catch (\Exception $e) {
            return $this->sendError('Error al eliminar la zona', $e->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     path="/api/zones",
     *     summary="Listar todas las zonas",
     *     tags={"Zonas"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de zonas",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/ZoneResource")
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        try {
            $zones = Zona::withCount(['pacientes', 'operators'])
                ->with('operators')
                ->paginate(10);
                
            return $this->sendResponse(
                ZoneResource::collection($zones),
                'Zones recuperades amb èxit'
            );
        } catch (\Exception $e) {
            return $this->sendError('Error al recuperar les zones', $e->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     path="/api/zones/{id}",
     *     summary="Obtener información de una zona",
     *     tags={"Zonas"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Información de la zona obtenida con éxito",
     *         @OA\JsonContent(ref="#/components/schemas/ZoneResource")
     *     )
     * )
     */
    public function show(Zona $zona)
    {
        try {
            $zona->loadCount(['pacientes', 'operators'])
                ->load(['operators']);
                
            return $this->sendResponse(
                new ZoneResource($zona),
                'Zona recuperada ambèxit'
            );
        } catch (\Exception $e) {
            return $this->sendError('Error al recuperar la zona', $e->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     path="/api/zones/{id}/patients",
     *     summary="Listar pacientes en una zona",
     *     tags={"Zonas"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de pacientes en la zona",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/PatientResource")
     *             )
     *         )
     *     )
     * )
     */
    public function getZonePatients(Zona $zona)
    {
        try {
            $patients = $zona->pacientes()
                ->with(['contactos', 'avisos'])
                ->paginate(10);
            dd($patients);
            return $this->sendResponse(
                PatientResource::collection($patients),
                'Pacients de la zona recuperats amb èxit'
            );
        } catch (\Exception $e) {
            return $this->sendError('Error al recuperar els pacients de la zona', $e->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     path="/api/zones/{id}/operators",
     *     summary="Listar operadores asignados a una zona",
     *     tags={"Zonas"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de operadores asignados a la zona",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/UserResource")
     *             )
     *         )
     *     )
     * )
     */
    public function getZoneOperators(Zona $zona)
    {
        $operators = $zona->operators()
            ->where('role', 'operator')
            ->paginate(10);
            
        return $this->sendResponse(
            UserResource::collection($operators),
            'Operadors de la zona recuperats amb èxit'
        );
    }
}
