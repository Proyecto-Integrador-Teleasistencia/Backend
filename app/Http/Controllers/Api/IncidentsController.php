<?php

namespace App\Http\Controllers\Api;

use App\Models\Incidencia;
use App\Http\Resources\IncidentResource;
use App\Http\Requests\StoreIncidentRequest;
use App\Http\Requests\UpdateIncidentRequest;

class IncidentsController extends BaseController
{
    /**
     * Mostrar un listado de incidencias.
     *
     * @return \Illuminate\Http\Response
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
     * Almacenar una nueva incidencia.
     *
     * @param  \App\Http\Requests\StoreIncidentRequest  $request
     * @return \Illuminate\Http\Response
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
     * Mostrar una incidencia específica.
     *
     * @param  \App\Models\Incidencia  $incidencia
     * @return \Illuminate\Http\Response
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
     * Actualizar una incidencia específica.
     *
     * @param  \App\Http\Requests\UpdateIncidentRequest  $request
     * @param  \App\Models\Incidencia  $incidencia
     * @return \Illuminate\Http\Response
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
     * Eliminar una incidencia específica.
     *
     * @param  \App\Models\Incidencia  $incidencia
     * @return \Illuminate\Http\Response
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
     * Obtener todas las incidencias de un paciente específico.
     *
     * @param  string  $patientId
     * @param  string  $patientId
     * @return \Illuminate\Http\Response
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
