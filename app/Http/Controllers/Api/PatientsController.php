<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Http\Resources\PatientResource;
use App\Http\Resources\ContactResource;
use App\Models\Paciente;
use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use Illuminate\Http\Request;

class PatientsController extends BaseController
{
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

    public function update(UpdatePatientRequest $request, $id)
    {
        $patient = Paciente::findOrFail($id);
        $this->authorize('update', $patient);   
        $validated = $request->validated();

        $patient->update($validated);

        return $this->sendResponse(new PatientResource($patient), 'Paciente actualitzat ambèxit', 200);
    }

    public function destroy($id)
    {
        try {
            $patient = Paciente::findOrFail($id);
            $this->authorize('delete', $patient);
            
            $patient = Paciente::findOrFail($id);
            
            // Eliminar registros relacionados manualmente por si acaso
            $patient->contactos()->delete();
            $patient->llamadas()->delete();
            
            // Eliminar el paciente
            $patient->delete();
            
            return $this->sendResponse([], 'Paciente eliminado correctamente', 200);
            
        } catch (\Exception $e) {
            return $this->sendError('Error al eliminar el paciente', [$e->getMessage()], 500);
        }
    }

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
