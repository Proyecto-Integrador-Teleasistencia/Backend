<?php

namespace App\Virtual\Resources;

/**
 * @OA\Schema(
 *     title="CallResource",
 *     description="Call resource",
 *     @OA\Xml(name="CallResource")
 * )
 */
class CallResource
{
    /**
     * @OA\Property(type="integer", example=1)
     */
    public $id;

    /**
     * @OA\Property(type="string", enum={"incoming", "outgoing"}, example="incoming")
     */
    public $type;

    /**
     * @OA\Property(type="string", enum={"scheduled", "in_progress", "completed", "cancelled"}, example="completed")
     */
    public $status;

    /**
     * @OA\Property(type="string", format="datetime", example="2024-02-18 10:00:00")
     */
    public $scheduled_for;

    /**
     * @OA\Property(type="string", format="datetime", example="2024-02-18 10:15:00")
     */
    public $completed_at;

    /**
     * @OA\Property(type="string", example="Regular check-up call")
     */
    public $description;

    /**
     * @OA\Property(type="integer", example=1)
     */
    public $patient_id;

    /**
     * @OA\Property(type="integer", example=1)
     */
    public $operator_id;

    /**
     * @OA\Property(
     *     type="object",
     *     ref="#/components/schemas/Patient"
     * )
     */
    public $patient;

    /**
     * @OA\Property(
     *     type="object",
     *     ref="#/components/schemas/User"
     * )
     */
    public $operator;
}
