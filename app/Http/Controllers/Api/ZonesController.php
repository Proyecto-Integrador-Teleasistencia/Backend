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
     *     summary="List all zones",
     *     tags={"Zones"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of zones",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Zone")
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        try {
            $zones = Zona::withCount(['pacientes', 'operator'])
                ->with('operator')
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
     *     summary="Get zone information",
     *     tags={"Zones"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Zone information retrieved successfully"
     *     )
     * )
     */
    public function show(Zona $zona)
    {
        try {
            $zona->loadCount(['pacientes', 'operator'])
                ->load(['operator']);
                
            return $this->sendResponse(
                new ZoneResource($zona),
                'Zona recuperada amb èxit'
            );
        } catch (\Exception $e) {
            return $this->sendError('Error al recuperar la zona', $e->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     path="/api/zones/{id}/patients",
     *     summary="List patients in a zone",
     *     tags={"Zones"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of patients in the zone"
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
     *     summary="List operators assigned to a zone",
     *     tags={"Zones"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of operators assigned to the zone"
     *     )
     * )
     */
    public function getZoneOperators(Zone $zone)
    {
        $operators = $zone->operators()
            ->where('role', 'operator')
            ->paginate(10);
            
        return $this->sendResponse(
            UserResource::collection($operators),
            'Operadors de la zona recuperats amb èxit'
        );
    }
}
