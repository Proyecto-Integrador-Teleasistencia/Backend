<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PatientResource;
use App\Models\Patient;
use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use Illuminate\Http\Request;

class PatientsController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Patient::class);

        $query = Patient::with('zone', 'contacts');

        // Si es operador, filtrar solo los pacientes de sus zonas
        if (auth()->user()->role === 'operator') {
            $zoneIds = auth()->user()->zones->pluck('id');
            $query->whereIn('zone_id', $zoneIds);
        }

        return PatientResource::collection($query->paginate(10));
    }

    public function store(StorePatientRequest $request)
    {
        $this->authorize('create', Patient::class);
        
        $validated = $request->validated();

        $patient = Patient::create($validated);

        return response()->json($patient, 201);
    }

    public function show($id)
    {
        $patient = Patient::with('zone', 'contacts')->findOrFail($id);
        $this->authorize('view', $patient);
        return response()->json($patient);
    }

    public function update(UpdatePatientRequest $request, $id)
    {
        $patient = Patient::findOrFail($id);
        $this->authorize('update', $patient);
        $validated = $request->validated([
            'name' => 'sometimes|required|string',
            'address' => 'sometimes|required|string',
            'dni' => 'sometimes|required|string|unique:patients,dni,' . $id,
            'health_card' => 'sometimes|required|string|unique:patients,health_card,' . $id,
            'phone' => 'sometimes|required|string|max:20',
            'email' => 'sometimes|required|email|unique:patients,email,' . $id,
            'zone_id' => 'sometimes|required|exists:zones,id',
            'personal_situation' => 'nullable|string',
            'health_condition' => 'nullable|string',
            'home_condition' => 'nullable|string',
            'autonomy_level' => 'nullable|string',
            'economic_situation' => 'nullable|string',
        ]);

        $patient->update($validated);

        return response()->json($patient);
    }

    public function destroy($id)
    {
        $patient = Patient::findOrFail($id);
        $this->authorize('delete', $patient);
        $patient->delete();

        return response()->json(['message' => 'Paciente eliminado correctamente']);
    }
}
