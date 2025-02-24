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
     *     path="/api/usuarios",
     *     summary="Obtener todos los usuarios",
     *     tags={"Usuarios"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de usuarios recuperada con éxito",
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
            'Llista d\'operadors recuperada ambèxit'
        );
    }

    /**
     * @OA\Post(
     *     path="/api/usuarios",
     *     summary="Crear un nuevo usuario",
     *     tags={"Usuarios"},
     *     security={{"bearerAuth":{}}},
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
     * @OA\Get(
     *     path="/api/usuarios/{id}",
     *     summary="Obtener un usuario por ID",
     *     tags={"Usuarios"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Operador recuperado exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/UserResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Operador no encontrado"
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
            'Operador recuperat ambèxit'
        );
    }

    /**
     * @OA\Put(
     *     path="/api/usuarios/{id}",
     *     summary="Actualizar un usuario",
     *     tags={"Usuarios"},
     *     security={{"bearerAuth":{}}},
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
    public function update(Request $request, User $operator)
    {
        $this->authorize('update', $operator);

        $validated = $request->validate();

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $operator->update($validated);
        
        if (isset($validated['zones'])) {
            $operator->zones()->sync($validated['zones']);
        }

        return $this->sendResponse(new UserResource($operator), 'Operador actualitzat ambèxit', 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/usuarios/{id}",
     *     summary="Eliminar un usuario",
     *     tags={"Usuarios"},
     *     security={{"bearerAuth":{}}},
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
