<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Models\Subcategoria;

use App\Http\Resources\SubcategoriaResource;
use OpenApi\Annotations as OA;

class SubcategoriasController extends BaseController
{
    /**
     * @OA\Get(
     *     path="/api/subcategorias",
     *     summary="Obtener todas las subcategorías",
     *     tags={"Subcategorias"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de subcategorías encontrada con éxito",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/SubcategoriaResource")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encontraron subcategorías"
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
            $subcategorias = Subcategoria::with('categoria')->get();
            return $this->sendResponse(SubcategoriaResource::collection($subcategorias), 'Subcategories encontrades ambèxit', 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->sendError('No s han trobat subcategories', 404);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/subcategorias/{id}",
     *     summary="Obtener una subcategoría por ID",
     *     tags={"Subcategorias"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la subcategoría",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Subcategoría encontrada con éxito",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/SubcategoriaResource")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Subcategoría no encontrada"
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
            $subcategoria = Subcategoria::with('categoria')->findOrFail($id);
            return $this->sendResponse(new SubcategoriaResource($subcategoria), 'Subcategoria recuperada ambèxit', 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->sendError('No s ha trobat la subcategoria amb el id: ' . $id, 404);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 500);
        }
    }
}
