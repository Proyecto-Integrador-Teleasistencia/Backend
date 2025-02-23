<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Models\Subcategoria;

use App\Http\Resources\SubcategoriaResource;

class SubcategoriasController extends BaseController
{
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
