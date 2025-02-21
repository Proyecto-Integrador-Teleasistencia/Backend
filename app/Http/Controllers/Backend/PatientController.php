<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PatientController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Paciente::class, 'patient');
    }

    public function index()
    {
        $patients = Paciente::when(auth()->user()->role !== 'admin', function ($query) {
            // Si no es admin, solo ver pacientes de sus zonas
            return $query->whereIn('zone_id', auth()->user()->zones->pluck('id'));
        })->with('zone')->paginate(10);

        return view('admin.patients.index', compact('patients'));
    }

    public function show(Paciente $patient)
    {
        $this->authorize('view', $patient);
        return view('admin.patients.show', compact('patient'));
    }

    public function update(Request $request, Paciente $patient)
    {
        $this->authorize('update', $patient);

        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'direccion' => 'required|string',
            'fecha_nacimiento' => 'required|date',
            'zona_id' => 'required|exists:zonas,id'
        ]);

        // Verificar si el operador puede gestionar la zona seleccionada
        if (auth()->user()->role !== 'admin') {
            Gate::authorize('manage-zone', $patient->zone);
        }

        $patient->update($validatedData);

        return redirect()->route('backend.patients.index')
            ->with('success', 'Paciente actualizado correctamente.');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Paciente::class);

        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'direccion' => 'required|string',
            'fecha_nacimiento' => 'required|date',
            'zona_id' => 'required|exists:zonas,id'
        ]);

        Paciente::create($validatedData);

        return redirect()->route('backend.patients.index')
            ->with('success', 'Paciente creado correctamente.');
    }

    public function destroy(Paciente $patient)
    {
        $this->authorize('delete', $patient);
        
        $patient->delete();

        return redirect()->route('backend.patients.index')
            ->with('success', 'Paciente eliminado correctamente.');
    }
}
