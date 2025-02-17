<?php

namespace App\Virtual\Resources;

/**
 * @OA\Schema(
 *     title="PatientResource",
 *     description="Patient resource",
 *     @OA\Xml(name="PatientResource")
 * )
 */
class PatientResource
{
    /**
     * @OA\Property(type="integer", example=1)
     */
    public $id;

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

    /**
     * @OA\Property(
     *     type="object",
     *     ref="#/components/schemas/Zone"
     * )
     */
    public $zone;

    /**
     * @OA\Property(
     *     type="array",
     *     @OA\Items(ref="#/components/schemas/Contact")
     * )
     */
    public $contacts;
}
