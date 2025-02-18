<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class OperatorsController extends Controller
{
    /**
     * Display a listing of the operators.
     */
    public function index()
    {
        $operators = User::where('role', 'operator')->paginate(10);
        return view('backend.operators.index', compact('operators'));
    }

    /**
     * Show the form for creating a new operator.
     */
    public function create()
    {
        $zones = Zone::all();
        return view('backend.operators.create', compact('zones'));
    }

    /**
     * Store a newly created operator in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'zone_id' => 'nullable|exists:zones,id'
        ]);

        $operator = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'zone_id' => $validated['zone_id'] ?? null,
            'role' => 'operator'
        ]);

        return redirect()->route('backend.operators.index')
            ->with('success', 'Operador creado correctamente');
    }

    /**
     * Display the specified operator.
     */
    public function show(User $operator)
    {
        if ($operator->role !== 'operator') {
            return redirect()->route('backend.operators.index')
                ->with('error', 'Usuario no encontrado');
        }

        return view('backend.operators.show', compact('operator'));
    }

    /**
     * Show the form for editing the specified operator.
     */
    public function edit(User $operator)
    {
        if ($operator->role !== 'operator') {
            return redirect()->route('backend.operators.index')
                ->with('error', 'Usuario no encontrado');
        }

        $zones = Zone::all();
        return view('backend.operators.edit', compact('operator', 'zones'));
    }

    /**
     * Update the specified operator in storage.
     */
    public function update(Request $request, User $operator)
    {
        if ($operator->role !== 'operator') {
            return redirect()->route('backend.operators.index')
                ->with('error', 'Usuario no encontrado');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $operator->id,
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'zone_id' => 'nullable|exists:zones,id',
            'is_active' => 'boolean'
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'zone_id' => $validated['zone_id'] ?? null,
            'is_active' => $request->boolean('is_active')
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $operator->update($updateData);

        return redirect()->route('backend.operators.index')
            ->with('success', 'Operador actualizado correctamente');
    }

    /**
     * Remove the specified operator from storage.
     */
    public function destroy(User $operator)
    {
        if ($operator->role !== 'operator') {
            return redirect()->route('backend.operators.index')
                ->with('error', 'Solo se pueden eliminar operadores');
        }

        $operator->delete();

        return redirect()->route('backend.operators.index')
            ->with('success', 'Operador eliminado correctamente');
    }
}
