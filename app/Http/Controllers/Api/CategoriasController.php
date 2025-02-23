<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Models\Categoria;
use App\Http\Resources\CategoriaResource;

class CategoriasController extends BaseController
{
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
