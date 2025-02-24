<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Models\Categoria;
use App\Http\Resources\CategoriaResource;
use OpenApi\Annotations as OA;

class CategoriasController extends BaseController
{
    /**
     * @OA\Get(
     *     path="/api/categorias",
     *     summary="Obtener todas las categorías",
     *     tags={"Categories"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de categorías recuperada con éxito",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(ref="#/components/schemas/CategoriaResource")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encontraron categorías"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor"
     *     )
     * )
     */
    public function index()
    {
        try {
            $categorias = Categoria::all();
            return $this->sendResponse(CategoriaResource::collection($categorias), 'Categorias recuperades ambèxit', 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->sendError('No s han trobat categories', 404);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/categorias/{id}",
     *     summary="Obtener detalles de una categoría",
     *     tags={"Categories"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la categoría a recuperar",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalles de la categoría recuperados con éxito",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/CategoriaResource")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Categoría no encontrada"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor"
     *     )
     * )
     */
    public function show($id)
    {
        try {
            $categoria = Categoria::findOrFail($id);
            return $this->sendResponse(new CategoriaResource($categoria), 'Categoria recuperada amb èxit', 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->sendError('No s ha trobat la categoria amb el id: ' . $id, 404);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 500);
        }
    }
}
