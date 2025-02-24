<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Http\Resources\PatientResource;
use App\Http\Resources\ContactResource;
use App\Models\Paciente;
use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OpenApi\Annotations as OA;

class PatientsController extends BaseController
{
    /**
     * @OA\Get(
     *     path="/api/patients",
     *     summary="Obtener todos los pacientes con filtros opcionales",
     *     tags={"Patients"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="nombre", in="query", description="Filtrar por nombre", @OA\Schema(type="string")),
     *     @OA\Parameter(name="dni", in="query", description="Filtrar por DNI", @OA\Schema(type="string")),
     *     @OA\Parameter(name="tarjeta_sanitaria", in="query", description="Filtrar por tarjeta sanitaria", @OA\Schema(type="string")),
     *     @OA\Parameter(name="telefono", in="query", description="Filtrar por teléfono", @OA\Schema(type="string")),
     *     @OA\Parameter(name="zona_id", in="query", description="Filtrar por zona", @OA\Schema(type="integer")),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de pacientes recuperada con éxito",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/PatientResource"))
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        try {
            $query = Paciente::query();

            if ($request->has('nombre')) {
                $query->where('nombre', 'LIKE', "%{$request->input('nombre')}%");
            }

            if ($request->has('dni')) {
                $query->where('dni', 'LIKE', "%{$request->input('dni')}%");
            }

            if ($request->has('tarjeta_sanitaria')) {
                $query->where('tarjeta_sanitaria', 'LIKE', "%{$request->input('tarjeta_sanitaria')}%");
            }

            if ($request->has('telefono')) {
                $query->where('telefono', 'LIKE', "%{$request->input('telefono')}%");
            }

            if ($request->has('zona_id')) {
                $query->where('zona_id', $request->input('zona_id'));
            }

            $patients = $query->get();
            return $this->sendResponse(PatientResource::collection($patients), 'Pacients recuperats ambèxit');
        } catch (\Exception $e) {
            return $this->sendError('Error al recuperar la llista de pacients', $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/patients",
     *     summary="Crear un nuevo paciente",
     *     tags={"Patients"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StorePatientRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Paciente creado exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/PatientResource")
     *     )
     * )
     */
    public function store(StorePatientRequest $request)
    {
        $this->authorize('create', Paciente::class);
        try {
            $validated = $request->validated();

            if ($validated === null) {
                return $this->sendError('Error al crear el paciente', 'No se ha podido crear el paciente', 422);
            }

            $patient = Paciente::create($validated);

            return $this->sendResponse(new PatientResource($patient), 'Paciente creat ambèxit', 201);
        } catch (\Exception $e) {
            return $this->sendError('Error al crear el paciente', $e->getMessage(), 422);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/patients/{id}",
     *     summary="Obtener detalles de un paciente",
     *     tags={"Patients"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(
     *         response=200,
     *         description="Detalles del paciente recuperados exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/PatientResource")
     *     )
     * )
     */
    public function show(Request $request, $id)
    {
        try {
            $query = Paciente::with('zona')->findOrFail($id);

            if ($request->has('nombre')) {
                $query->where('nombre', 'LIKE', "%{$request->input('nombre')}%");
            }

            if ($request->has('dni')) {
                $query->where('dni', 'LIKE', "%{$request->input('dni')}%");
            }

            if ($request->has('tarjeta_sanitaria')) {
                $query->where('tarjeta_sanitaria', 'LIKE', "%{$request->input('tarjeta_sanitaria')}%");
            }

            if ($request->has('telefono')) {
                $query->where('telefono', 'LIKE', "%{$request->input('telefono')}%");
            }

            if ($request->has('zona_id')) {
                $query->where('zona_id', $request->input('zona_id'));
            }

            return $this->sendResponse(new PatientResource($query), 'Paciente recuperat ambèxit', 200);
        } catch (\Exception $e) {
            return $this->sendError('Error al recuperar el paciente', $e->getMessage(), 404);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/patients/{id}",
     *     summary="Actualizar un paciente",
     *     tags={"Patients"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdatePatientRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Paciente actualizado exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/PatientResource")
     *     )
     * )
     */
    public function update(UpdatePatientRequest $request, $id)
    {
        $patient = Paciente::findOrFail($id);
        $this->authorize('update', $patient);
        $validated = $request->validated();

        $patient->update($validated);

        return $this->sendResponse(new PatientResource($patient), 'Paciente actualitzat ambèxit', 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/patients/{id}",
     *     summary="Eliminar un paciente",
     *     tags={"Patients"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(
     *         response=204,
     *         description="Paciente eliminado exitosamente"
     *     )
     * )
     */
    public function destroy(Paciente $paciente)
    {
        $paciente->delete();
        return response()->noContent();
    }

    /**
     * @OA\Get(
     *     path="/api/zones/{zone_id}/patients",
     *     summary="Obtener pacientes por zona",
     *     tags={"Patients"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="zone_id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de pacientes de la zona recuperada con éxito",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/PatientResource"))
     *         )
     *     )
     * )
     */
    public function getPatientsByZones($zoneId)
    {
        try {
            $patients = Paciente::where('zona_id', $zoneId)->get();
            return $this->sendResponse(PatientResource::collection($patients), 'Pacients recuperats ambèxit');
        } catch (\Exception $e) {
            return $this->sendError('Error al recuperar la lista de pacientes por zona', $e->getMessage(), 404);
        }
    }
}
