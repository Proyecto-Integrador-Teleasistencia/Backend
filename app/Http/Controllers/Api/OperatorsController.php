<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use OpenApi\Annotations as OA;

class OperatorsController extends BaseController
{
    /**
     * @OA\Get(
     *     path="/api/operators",
     *     summary="Obtener todos los operadores",
     *     tags={"Operators"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de operadores recuperada con éxito",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(ref="#/components/schemas/UserResource")
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $users = User::where('role', 'operator')
            ->with('zona')
            ->get();
        return $this->sendResponse(
            UserResource::collection($users),
            'Llista d\'operadors recuperada amb èxit'
        );
    }

    /**
     * @OA\Post(
     *     path="/api/operators",
     *     summary="Crear un nuevo operador",
     *     tags={"Operators"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreOperatorRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Operador creado exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/UserResource")
     *     )
     * )
     */
    public function store(StoreOperatorRequest $request)
    {
        $validated = $request->validated();

        $validated['role'] = 'operator';
        $validated['password'] = Hash::make($validated['password']);

        $operator = User::create($validated);
        
        // Asignar zonas al operador
        if (isset($validated['zona'])) {
            $operator->zona()->associate($validated['zona']);
            $operator->save();
        }

        return $this->sendResponse(
            new UserResource($operator),
            'Operador creat ambèxit',
            201
        );
    }

    /**
     * @OA\Put(
     *     path="/api/operators/{id}",
     *     summary="Actualizar un operador",
     *     tags={"Operators"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateOperatorRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Operador actualizado exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/UserResource")
     *     )
     * )
     */
    public function show($id)
    {
        $operator = User::where('role', 'operator')
            ->with('zona')
            ->findOrFail($id);

        return $this->sendResponse(
            new UserResource($operator),
            'Operador recuperat amb èxit'
        );
    }

    /**
     * @OA\Delete(
     *     path="/api/operators/{id}",
     *     summary="Eliminar un operador",
     *     tags={"Operators"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Operador eliminado exitosamente"
     *     )
     * )
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
     * @OA\Delete(
     *     path="/api/operators/{id}",
     *     summary="Eliminar un operador",
     *     tags={"Operators"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Operador eliminado exitosamente"
     *     )
     * )
     */
    public function destroy(User $operator)
    {
        $operator->delete();

        return $this->sendResponse(null, 'Operador eliminat amb èxit', 204);
    }
}
