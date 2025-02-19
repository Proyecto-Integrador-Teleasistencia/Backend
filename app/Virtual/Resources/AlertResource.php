<?php

namespace App\Virtual\Resources;

/**
 * @OA\Schema(
 *     title="AlertResource",
 *     description="Alert resource",
 *     @OA\Xml(name="AlertResource")
 * )
 */
class AlertResource
{
    /**
     * @OA\Property(type="integer", example=1)
     */
    public $id;

    /**
     * @OA\Property(type="string", enum={"alert", "alarm"}, example="alert")
     */
    public $type;

    /**
     * @OA\Property(type="string", enum={"pending", "in_progress", "resolved"}, example="pending")
     */
    public $status;

    /**
     * @OA\Property(type="string", example="Patient needs immediate attention")
     */
    public $description;

    /**
     * @OA\Property(type="string", enum={"low", "medium", "high"}, example="high")
     */
    public $priority;

    /**
     * @OA\Property(type="integer", example=1)
     */
    public $patient_id;

    /**
     * @OA\Property(type="integer", example=1)
     */
    public $operator_id;

    /**
     * @OA\Property(type="string", format="datetime", example="2024-02-18 10:00:00")
     */
    public $resolved_at;

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
