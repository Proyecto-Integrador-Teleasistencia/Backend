<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Zona;
use Illuminate\Http\Request;
use App\Http\Requests\Backend\StoreZonaRequest;
use App\Http\Requests\Backend\UpdateZonaRequest;

class ZonasController extends Controller
{
    /**
     * Muestra un listado de las zonas.
     */
    public function index()
    {
        $zonas = Zona::withCount('operator')->paginate(10);
        return view('backend.zonas.index', compact('zonas'));
    }

    /**
     * Muestra el formulario para crear una nueva zona.
     */
    public function create()
    {
        $zonas = Zona::all();
        return view('backend.zonas.create', compact('zonas'));
    }

    /**
     * Almacena una nueva zona en la base de datos.
     */
    public function store(StoreZonaRequest $request)
    {
        $validated = $request->validated();
        $validated['activa'] = $request->has('activa');

        Zona::create($validated);

        return redirect()->route('backend.zonas.index')
            ->with('success', 'Zona creada correctamente');
    }

    /**
     * Muestra una zona específica.
     */
    public function show(Zona $zona)
    {
        $zona->load('operator');
        return view('backend.zonas.show', compact('zona'));
    }

    /**
     * Muestra el formulario para editar una zona.
     */
    public function edit(Zona $zona)
    {
        return view('backend.zonas.edit', compact('zona'));
    }

    /**
     * Actualiza una zona específica en la base de datos.
     */
    public function update(UpdateZonaRequest $request, Zona $zona)
    {
        $validated = $request->validated();
        $validated['activa'] = $request->has('activa');

        $zona->update($validated);

        return redirect()->route('backend.zonas.index')
            ->with('success', 'Zona actualizada correctamente');
    }

    /**
     * Elimina una zona específica de la base de datos.
     */
    public function destroy(Zona $zona)
    {
        if ($zona->operator()->exists()) {
            return redirect()->route('backend.zonas.index')
                ->with('error', 'No se puede eliminar una zona que tiene operadores asignados');
        }

        $zona->delete();

        return redirect()->route('backend.zonas.index')
            ->with('success', 'Zona eliminada correctamente');
    }
}
