<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="PaginatedCollection",
 *     description="Esquema de respuesta paginada de recursos",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         description="Colección de datos paginados",
 *         @OA\Items(type="object")
 *     ),
 *     @OA\Property(
 *         property="pagination",
 *         type="object",
 *         description="Información de la paginación",
 *         @OA\Property(
 *             property="total",
 *             type="integer",
 *             description="Número total de elementos disponibles",
 *             example=100
 *         ),
 *         @OA\Property(
 *             property="per_page",
 *             type="integer",
 *             description="Número de elementos por página",
 *             example=10
 *         ),
 *         @OA\Property(
 *             property="current_page",
 *             type="integer",
 *             description="Número de la página actual",
 *             example=1
 *         ),
 *         @OA\Property(
 *             property="last_page",
 *             type="integer",
 *             description="Número total de páginas disponibles",
 *             example=10
 *         ),
 *         @OA\Property(
 *             property="from",
 *             type="integer",
 *             description="Índice del primer elemento en la página actual",
 *             example=1
 *         ),
 *         @OA\Property(
 *             property="to",
 *             type="integer",
 *             description="Índice del último elemento en la página actual",
 *             example=10
 *         )
 *     )
 * )
 */

class PaginatedCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'data' => $this->collection,
            'pagination' => [
                'total' => $this->total(),
                'per_page' => $this->perPage(),
                'current_page' => $this->currentPage(),
                'last_page' => $this->lastPage(),
                'from' => $this->firstItem(),
                'to' => $this->lastItem(),
            ],
        ];
    }

    /**
     * Customize the outgoing response for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Http\Response  $response
     */
    public function withResponse($request, $response): void
    {
        $jsonResponse = json_decode($response->getContent(), true);
        unset($jsonResponse['links'], $jsonResponse['meta']);
        $response->setContent(json_encode($jsonResponse));
    }
}
