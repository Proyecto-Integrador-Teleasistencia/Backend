<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 * required={"id", "nombre"},
 * @OA\Xml(name="Subcategoria"),
 * @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 * @OA\Property(property="nombre", type="string", readOnly="true", description="Nombre de la subcategoría"),
 * @OA\Property(property="categoria_id", type="integer", readOnly="true", description="Id de la categoría"),
 * )
 */
class Subcategoria extends Model
{
    /** @use HasFactory<\Database\Factories\SubcategoryFactory> */
    use HasFactory;

    protected $fillable = ['nombre', 'categoria_id'];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }
}
