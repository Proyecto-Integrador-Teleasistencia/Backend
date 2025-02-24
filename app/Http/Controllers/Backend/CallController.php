<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Llamada;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CallController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Llamada::class, 'call');
    }

    public function index()
    {
        return view('admin.calls.index');
    }

    public function store(Request $request)
    {
        $patient = Paciente::findOrFail($request->patient_id);
        
        if ($request->tipo_llamada === 'saliente') {
            $this->authorize('makeOutgoingCall', $patient);
        }

        $validatedData = $request->validate([
            'patient_id' => 'required|exists:pacientes,id',
            'tipo_llamada' => 'required|in:entrante,saliente',
            'categoria_id' => 'required|exists:categorias,id',
            'subcategoria_id' => 'nullable|exists:subcategorias,id',
            'descripcion' => 'nullable|string',
            'duracion' => 'required|integer|min:0',
            'estado' => 'required|in:en_curso,completada,cancelada'
        ]);

        $call = Llamada::create($validatedData + [
            'operador_id' => auth()->id(),
            'fecha_hora' => now(),
        ]);

        return redirect()->route('backend.calls.index')
            ->with('success', 'Llamada registrada correctamente.');
    }

    public function update(Request $request, Llamada $call)
    {
        $this->authorize('update', $call);

        $validatedData = $request->validate([
            'estado' => 'required|in:en_curso,completada,cancelada',
            'descripcion' => 'nullable|string',
            'duracion' => 'required|integer|min:0'
        ]);

        $call->update($validatedData);

        return redirect()->route('backend.calls.index')
            ->with('success', 'Llamada actualizada correctamente.');
    }

    public function destroy(Llamada $call)
    {
        $this->authorize('delete', $call);
        
        $call->delete();

        return redirect()->route('backend.calls.index')
            ->with('success', 'Llamada eliminada correctamente.');
    }
}
