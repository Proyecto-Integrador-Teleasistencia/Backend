<?php

namespace App\Virtual\Requests;

/**
 * @OA\Schema(
 *     title="Store Patient Request",
 *     description="Store Patient request body data",
 *     type="object",
 *     required={"name", "surname", "email", "phone", "zone_id"}
 * )
 */
class StorePatientRequest
{
    /**
     * @OA\Property(type="string", example="John")
     */
    public $name;

    /**
     * @OA\Property(type="string", example="Doe")
     */
    public $surname;

    /**
     * @OA\Property(type="string", format="email", example="john@example.com")
     */
    public $email;

    /**
     * @OA\Property(type="string", example="+34123456789")
     */
    public $phone;

    /**
     * @OA\Property(type="string", example="123 Street")
     */
    public $address;

    /**
     * @OA\Property(type="integer", example=1)
     */
    public $zone_id;
}
