<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class OperatorsController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::where('role', 'operator')
            ->with('zonas')
            ->get();
        return $this->sendResponse(
            UserResource::collection($users),
            'Llista d\'operadors recuperada amb èxit'
        );
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

        return $this->sendResponse(
            new UserResource($operator),
            'Operador creat amb èxit',
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $operator = User::where('role', 'operator')
            ->with('zonas')
            ->findOrFail($id);

        return $this->sendResponse(
            new UserResource($operator),
            'Operador recuperat amb èxit'
        );
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

        return $this->sendResponse(new UserResource($operator), 'Operador actualitzat amb èxit', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $operator)
    {
        $operator->delete();

        return $this->sendResponse(null, 'Operador eliminat amb èxit', 204);
    }
}
