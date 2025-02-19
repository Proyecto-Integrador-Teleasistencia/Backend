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

        $patients = $query->paginate(10);
        return $this->sendResponse(PatientResource::collection($patients), 'Pacients recuperats ambèxit');
    }

    public function store(StorePatientRequest $request)
    {
        $validated = $request->validated();

        $patient = Paciente::create($validated);

        return $this->sendResponse(new PatientResource($patient), 'Paciente creat ambèxit', 201);
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
}
