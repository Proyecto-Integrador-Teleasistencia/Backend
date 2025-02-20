<?php

namespace App\Virtual\Models;

/**
 * @OA\Schema(
 *     title="Patient",
 *     description="Patient model",
 *     @OA\Xml(name="Patient")
 * )
 */
class Patient
{
    /**
     * @OA\Property(type="integer", example=1)
     */
    private $id;

    /**
     * @OA\Property(type="string", example="John")
     */
    private $name;

    /**
     * @OA\Property(type="string", example="Doe")
     */
    private $surname;

    /**
     * @OA\Property(type="string", format="email", example="john@example.com")
     */
    private $email;

    /**
     * @OA\Property(type="string", example="+34123456789")
     */
    private $phone;

    /**
     * @OA\Property(type="string", example="123 Street")
     */
    private $address;

    /**
     * @OA\Property(type="integer", example=1)
     */
    private $zone_id;

    /**
     * @OA\Property(type="string", format="datetime", example="2024-02-18 10:00:00")
     */
    private $created_at;

    /**
     * @OA\Property(type="string", format="datetime", example="2024-02-18 10:00:00")
     */
    private $updated_at;
}
