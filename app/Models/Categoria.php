<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"id", "nombre"},
 *     @OA\Xml(name="Categoria"),
 *     @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 *     @OA\Property(property="nombre", type="string", readOnly="true", description="Nombre de la categoría"),
 *     @OA\Property(property="descripcion", type="string", readOnly="true", description="Descripción de la categoría"),
 *     @OA\Property(property="subcategorias", type="array", readOnly="true", description="Subcategorías de esta categoría", @OA\Items(ref="#/components/schemas/Subcategoria")),
 * )
 */
class Categoria extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;

    protected $table = 'categorias';

    protected $fillable = [
        'id',
        'nombre',
        'descripcion',
    ];

    public function subcategorias()
    {
        return $this->hasMany(Subcategoria::class);
    }
}   
