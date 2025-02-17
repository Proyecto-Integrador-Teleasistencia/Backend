<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Http\Resources\PatientResource;
use App\Models\Patient;
use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use Illuminate\Http\Request;

class PatientsController extends BaseController
{
    public function index()
    {
        return PatientResource::collection(Patient::paginate(10));
    }

    public function store(StorePatientRequest $request)
    {
        $validated = $request->validated();

        $patient = Patient::create($validated);

        return $this->sendResponse(new PatientResource($patient), 'Paciente creat ambèxit', 201);
    }

    public function show(Patient $patient)
    {
        return $this->sendResponse(new PatientResource($patient), 'Paciente recuperat ambèxit', 200);
    }

    public function update(UpdatePatientRequest $request, Patient $patient)
    {
        $validated = $request->validated();

        $patient->update($validated);

        return $this->sendResponse(new PatientResource($patient), 'Paciente actualitzat ambèxit', 200);
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();

        return $this->sendResponse([], 'Paciente eliminado correctamente', 200);
    }
}
