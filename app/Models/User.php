<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={"id", "nombre", "email", "role"},
 *     @OA\Xml(name="User"),
 *     @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 *     @OA\Property(property="nombre", type="string", readOnly="true", description="Nombre del usuario"),
 *     @OA\Property(property="email", type="string", readOnly="true", description="Correo electrónico del usuario"),
 *     @OA\Property(property="role", type="string", readOnly="true", description="Rol del usuario"),
 *     @OA\Property(property="telefono", type="string", readOnly="true", description="Teléfono del usuario"),
 *     @OA\Property(property="fecha_contratacion", type="string", readOnly="true", description="Fecha de contratación del usuario", format="date-time"),
 *     @OA\Property(property="fecha_baja", type="string", readOnly="true", description="Fecha de baja del usuario", format="date-time"),
 *     @OA\Property(property="is_admin", type="boolean", readOnly="true", description="Indica si el usuario es administrador"),
 *     @OA\Property(property="zona_id", type="integer", readOnly="true", description="Id de la zona del usuario", example="1"),
 * )
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nombre',
        'email',
        'role',
        'telefono',
        'fecha_contratacion',
        'fecha_baja',
        'password',
        'is_admin',
        'zona_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $dates = ['fecha_contratacion', 'fecha_baja'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function zona()
    {
        return $this->belongsTo(Zona::class);
    }

    public function llamadas()
    {
        return $this->hasMany(Llamada::class);
    }
}
