<?php

namespace App\Http\Controllers\Api;

use App\Models\Incidencia;
use App\Http\Resources\IncidentResource;
use App\Http\Requests\StoreIncidentRequest;
use App\Http\Requests\UpdateIncidentRequest;
use OpenApi\Annotations as OA;

class IncidentsController extends BaseController
{
    /**
     * @OA\Get(
     *     path="/api/incidents",
     *     summary="Obtener todas las incidencias",
     *     tags={"Incidents"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de incidencias recuperada con éxito",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(ref="#/components/schemas/IncidentResource")
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        try {
            $incidents = Incidencia::with(['paciente', 'operador'])->get();
            return $this->sendResponse(IncidentResource::collection($incidents), 'Incidències recuperades ambèxit');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->sendError('No s han trobat incidències', [], 404);
        } catch (\Exception $e) {
            return $this->sendError('Error al recuperar les incidències', $e->getMessage(), 422);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/incidents",
     *     summary="Crear una nueva incidencia",
     *     tags={"Incidents"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreIncidentRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Incidencia creada exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/IncidentResource")
     *     )
     * )
     */
    public function store(StoreIncidentRequest $request)
    {
        try {
            $validated = $request->validated();

            $incident = Incidencia::create($validated);
            return $this->sendResponse(new IncidentResource($incident), 'Incidència creada amb èxit', 201);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->sendError('No s ha trobat el pacient amb el id: ' . $request->patient_id, [], 404);
        } catch (\Exception $e) {
            return $this->sendError('Error creant la incidència', [], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/incidents/{id}",
     *     summary="Obtener detalles de una incidencia",
     *     tags={"Incidents"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalles de la incidencia recuperados exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/IncidentResource")
     *     )
     * )
     */
    public function show($id)
    {
        try {
            $incidencia = Incidencia::findOrFail($id);
            $incidencia->load(['paciente', 'operador']);
            return $this->sendResponse(new IncidentResource($incidencia), 'Incidència recuperada ambèxit');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->sendError('No s ha trobat la incidència amb el id: ' . $id, [], 404);
        } catch (\Exception $e) {
            return $this->sendError('Error al recuperar la incidència', $e->getMessage(), 422);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/incidents/{id}",
     *     summary="Actualizar una incidencia",
     *     tags={"Incidents"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateIncidentRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Incidencia actualizada exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/IncidentResource")
     *     )
     * )
     */
    public function update(UpdateIncidentRequest $request, Incidencia $incidencia)
    {
        try {
            $incidencia->update($request->validated());
            return $this->sendResponse(new IncidentResource($incidencia), 'Incidència actualitzada ambèxit');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->sendError('No s ha trobat la incidència amb el id: ' . $incidencia->id, [], 404);
        } catch (\Exception $e) {
            return $this->sendError('Error al actualitzar la incidència', $e->getMessage(), 422);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/incidents/{id}",
     *     summary="Eliminar una incidencia",
     *     tags={"Incidents"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Incidencia eliminada exitosamente"
     *     )
     * )
     */
    public function destroy(Incidencia $incidencia)
    {
        try {
            $incidencia->delete();
            return $this->sendResponse([], 'Incidència eliminada ambèxit');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->sendError('No s ha trobat la incidència amb el id: ' . $incidencia->id, [], 404);
        } catch (\Exception $e) {
            return $this->sendError('Error al eliminar la incidència', $e->getMessage(), 422);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/patients/{patient_id}/incidents",
     *     summary="Obtener todas las incidencias de un paciente",
     *     tags={"Incidents"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="patient_id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de incidencias del paciente recuperada con éxito",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(ref="#/components/schemas/IncidentResource")
     *             )
     *         )
     *     )
     * )
     */
    public function getPatientIncidents(string $patientId)
    {
        try {
        $incidents = Incidencia::where('paciente_id', $patientId)
            ->with(['operador'])
            ->get();
        return $this->sendResponse(IncidentResource::collection($incidents), 'Incidències del pacient recuperades ambèxit');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->sendError('No s ha trobat el pacient amb el id: ' . $patientId, [], 404);
        } catch (\Exception $e) {
            return $this->sendError('Error al recuperar les incidències del pacient', $e->getMessage(), 422);
        }
    }
}
