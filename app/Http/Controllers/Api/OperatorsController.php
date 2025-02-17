<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class OperatorsController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(User::where('role', 'operator')
            ->with('zones')
            ->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOperatorRequest $request)
    {
        $validated = $request->validated();

        $validated['role'] = 'operator';
        $validated['password'] = Hash::make($validated['password']);

        $operator = User::create($validated);
        
        // Asignar zonas al operador
        if (isset($validated['zones'])) {
            $operator->zones()->attach($validated['zones']);
        }

        return response()->json($operator, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $operator = User::where('role', 'operator')->findOrFail($id);
        return response()->json($operator);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $operator = User::where('role', 'operator')->findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'sometimes|required|string|min:8',
            'phone' => 'sometimes|required|string|max:20',
            'shift' => 'sometimes|required|string|in:morning,afternoon,night',
            'status' => 'sometimes|required|string|in:active,inactive,on_leave',
            'hire_date' => 'sometimes|required|date',
            'termination_date' => 'nullable|date|after:hire_date',
            'zone_id' => 'nullable|exists:zones,id',
            'zones' => 'sometimes|required|array',
            'zones.*' => 'required|exists:zones,id',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $operator->update($validated);
        
        // Actualizar zonas si se proporcionaron
        if (isset($validated['zones'])) {
            $operator->zones()->sync($validated['zones']);
        }

        return response()->json($operator);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $operator = User::where('role', 'operator')->findOrFail($id);
        $operator->delete();

        return response()->json(null, 204);
    }
}
