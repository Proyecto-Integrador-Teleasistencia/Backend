<?php

namespace App\Virtual\Models;

/**
 * @OA\Schema(
 *     title="User",
 *     description="User model",
 *     @OA\Xml(name="User")
 * )
 */
class User
{
    /**
     * @OA\Property(type="integer", example=1)
     */
    private $id;

    /**
     * @OA\Property(type="string", example="John Doe")
     */
    private $name;

    /**
     * @OA\Property(type="string", format="email", example="john@example.com")
     */
    private $email;

    /**
     * @OA\Property(type="string", example="operator")
     * @var string
     */
    private $role;

    /**
     * @OA\Property(type="string", format="datetime", example="2024-02-18 10:00:00")
     */
    private $created_at;

    /**
     * @OA\Property(type="string", format="datetime", example="2024-02-18 10:00:00")
     */
    private $updated_at;
}
