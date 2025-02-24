<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Zona;
use Illuminate\Http\Request;
use App\Http\Requests\Backend\StoreZonaRequest;
use App\Http\Requests\Backend\UpdateZonaRequest;

class ZonasController extends Controller
{

    public function index()
    {
        $zonas = Zona::withCount('operators')->paginate(10);
        return view('backend.zonas.index', compact('zonas'));
    }

    public function create()
    {
        $zonas = Zona::all();
        return view('backend.zonas.create', compact('zonas'));
    }

    public function store(StoreZonaRequest $request)
    {
        $validated = $request->validated();
        $validated['activa'] = $request->has('activa');

        Zona::create($validated);

        return redirect()->route('backend.zonas.index')
            ->with('success', 'Zona creada correctamente');
    }

    public function show(Zona $zona)
    {
        $zona->load('operators');
        return view('backend.zonas.show', compact('zona'));
    }

    public function edit(Zona $zona)
    {
        return view('backend.zonas.edit', compact('zona'));
    }

    public function update(UpdateZonaRequest $request, Zona $zona)
    {
        $validated = $request->validated();
        $validated['activa'] = $request->has('activa');

        $zona->update($validated);

        return redirect()->route('backend.zonas.index')
            ->with('success', 'Zona actualizada correctamente');
    }

    public function destroy(Zona $zona)
    {
        if ($zona->operators()->exists()) {
            return redirect()->route('backend.zonas.index')
                ->with('error', 'No se puede eliminar una zona que tiene operadores asignados');
        }

        $zona->delete();

        return redirect()->route('backend.zonas.index')
            ->with('success', 'Zona eliminada correctamente');
    }
}
