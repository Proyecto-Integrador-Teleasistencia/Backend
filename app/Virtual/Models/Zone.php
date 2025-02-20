<?php

namespace App\Virtual\Models;

/**
 * @OA\Schema(
 *     title="Zone",
 *     description="Zone model",
 *     @OA\Xml(name="Zone")
 * )
 */
class Zone
{
    /**
     * @OA\Property(type="integer", example=1)
     */
    private $id;

    /**
     * @OA\Property(type="string", example="North Zone")
     */
    private $name;

    /**
     * @OA\Property(type="string", example="Coverage area for northern region")
     */
    private $description;

    /**
     * @OA\Property(type="integer", example=25)
     */
    private $patients_count;

    /**
     * @OA\Property(type="integer", example=5)
     */
    private $operators_count;

    /**
     * @OA\Property(type="string", format="datetime", example="2024-02-18 10:00:00")
     */
    private $created_at;

    /**
     * @OA\Property(type="string", format="datetime", example="2024-02-18 10:00:00")
     */
    private $updated_at;
}
