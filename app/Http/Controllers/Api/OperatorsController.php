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
        return $this->sendResponse(User::where('role', 'operator')
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

        return $this->sendResponse($operator, 'Operador creat ambèxit', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $operator)
    {
        return $this->sendResponse(new OperatorResource($operator));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $operator)
    {
        $this->authorize('update', $operator);

        $validated = $request->validate();

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $operator->update($validated);
        
        // Actualizar zonas si se proporcionaron
        if (isset($validated['zones'])) {
            $operator->zones()->sync($validated['zones']);
        }

        return $this->sendResponse(new OperatorResource($operator), 'Operador actualitzat ambèxit', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $operator)
    {
        $operator->delete();

        return $this->sendResponse(null, 'Operador eliminat ambèxit', 204);
    }
}
