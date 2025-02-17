<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Models\Zone;
use App\Http\Resources\ZoneResource;
use App\Http\Resources\PatientResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class ZonesController extends BaseController
{
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
        $zones = Zone::withCount(['patients', 'operators'])
            ->with('operators')
            ->paginate(10);
            
        return $this->sendResponse(
            ZoneResource::collection($zones),
            'Zones recuperades amb èxit'
        );
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
    public function show(Zone $zone)
    {
        $zone->loadCount(['patients', 'operators'])
            ->load(['operators']);
            
        return $this->sendResponse(
            new ZoneResource($zone),
            'Zona recuperada amb èxit'
        );
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
    public function getZonePatients(Zone $zone)
    {
        $patients = $zone->patients()
            ->with(['contacts', 'alerts'])
            ->paginate(10);
            
        return $this->sendResponse(
            PatientResource::collection($patients),
            'Pacients de la zona recuperats amb èxit'
        );
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
