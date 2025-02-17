<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
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
        return view('backend.operators.create');
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
        ]);

        $operator = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'role' => 'operator'
        ]);

        return redirect()->route('backend.operators.index')
            ->with('success', 'Operador creado correctamente');
    }

    /**
     * Show the form for editing the specified operator.
     */
    public function edit(User $operator)
    {
        return view('backend.operators.edit', compact('operator'));
    }

    /**
     * Update the specified operator in storage.
     */
    public function update(Request $request, User $operator)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $operator->id,
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
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
