<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Zona;
use App\Http\Requests\Backend\StoreOperatorRequest;
use App\Http\Requests\Backend\UpdateOperatorRequest;
use App\Http\Resources\Backend\OperatorResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Gate;

class OperatorsController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the operators.
     */
    public function index()
    {
        $operators = User::where('role', 'operator')->with('zona')->paginate(10);
        return view('backend.operators.index', compact('operators'));
    }

    /**
     * Show the form for creating a new operator.
     */
    public function create()
    {
        $this->authorize('create', User::class);
        $zonas = Zona::all();
        return view('backend.operators.create', compact('zonas'));
    }

    /**
     * Store a newly created operator in storage.
     */
    public function store(StoreOperatorRequest $request)
    {
        $this->authorize('create', User::class);
        $validated = $request->validated();

        // Preparar los datos para crear el operador
        $operatorData = [
            'nombre' => $validated['nombre'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'telefono' => $validated['telefono'] ?? null,
            'zona_id' => $validated['zona_id'] ?? null,
            'role' => 'operator'
        ];

        $operator = User::create($operatorData);

        return redirect()->route('backend.operators.index')
            ->with('success', 'Operador creado correctamente');
    }

    /**
     * Display the specified operator.
     */
    public function show(User $operator)
    {
        if ($operator->role !== 'operator') {
            abort(404, 'Usuario no encontrado');
        }

        return view('backend.operators.show', compact('operator'));
    }

    /**
     * Show the form for editing the specified operator.
     */
    public function edit(User $operator)
    {
        $this->authorize('update', $operator);

        if ($operator->role !== 'operator') {
            abort(404, 'Usuario no encontrado');
        }

        $zonas = Zona::all();

        return view('backend.operators.edit', compact('operator', 'zonas'));
    }

    /**
     * Update the specified operator in storage.
     */
    public function update(UpdateOperatorRequest $request, User $operator)
    {
        $this->authorize('update', $operator);
        if ($operator->role !== 'operator') {
            abort(404, 'Usuario no encontrado');
        }

        $validated = $request->validated();

        // Preparar los datos de actualización
        $updateData = [
            'nombre' => $validated['nombre'],
            'email' => $validated['email'],
            'telefono' => $validated['telefono'] ?? null,
            'zona_id' => $validated['zona_id'] ?? null,
            'role' => 'operator'
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $operator->fill($updateData);
        $operator->save();

        return redirect()->route('backend.operators.index')
            ->with('success', 'Operador actualizado correctamente');
    }

    /**
     * Remove the specified operator from storage.
     */
    public function destroy(User $operator)
    {
        $this->authorize('delete', $operator);
        if ($operator->role !== 'operator') {
            abort(404, 'Usuario no encontrado');
        }

        $operator->delete();

        return redirect()->route('backend.operators.index')
            ->with('success', 'Operador eliminado correctamente');
    }
}