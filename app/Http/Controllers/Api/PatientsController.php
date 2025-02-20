<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Http\Resources\PatientResource;
use App\Models\Paciente;
use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use Illuminate\Http\Request;

class PatientsController extends BaseController
{
    public function index(Request $request)
    {
        $query = Paciente::query();

        if ($request->has('zona_id')) {
            $query->where('zona_id', $request->zona_id);
        }

        $patients = $query->get();
        return $this->sendResponse(PatientResource::collection($patients), 'Pacients recuperats ambèxit');
    }

    public function store(StorePatientRequest $request)
    {
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

    public function show($id)
    {
        $patient = Paciente::with('zona')->findOrFail($id);
        return $this->sendResponse(new PatientResource($patient), 'Paciente recuperat ambèxit', 200);
    }

    public function update(UpdatePatientRequest $request, $id)
    {
        $patient = Paciente::findOrFail($id);   
        $validated = $request->validated();

        $patient->update($validated);

        return $this->sendResponse(new PatientResource($patient), 'Paciente actualitzat ambèxit', 200);
    }

    public function destroy(Paciente $patient)
    {
        $patient->delete();

        return $this->sendResponse([], 'Paciente eliminado correctamente', 200);
    }

    public function getPatientsByZones($zoneId)
    {
        $patients = Paciente::where('zona_id', $zoneId)->get();
        return $this->sendResponse(PatientResource::collection($patients), 'Pacients recuperats ambèxit');
    }
}
