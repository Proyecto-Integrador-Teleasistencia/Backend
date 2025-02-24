<?php

namespace App\Providers;

use App\Models\Llamada;
use App\Models\Paciente;
use App\Models\Zona;
use App\Models\Aviso;
use App\Models\Contacto;
use App\Models\User;
use App\Policies\LlamadaPolicy;
use App\Policies\PacientePolicy;
use App\Policies\ZonaPolicy;
use App\Policies\AvisoPolicy;
use App\Policies\ContactoPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        \App\Models\Zona::class => \App\Policies\ZonaPolicy::class,
        Paciente::class => PacientePolicy::class,
        Llamada::class => LlamadaPolicy::class,
        Zona::class => ZonaPolicy::class,
        Aviso::class => AvisoPolicy::class,
        Contacto::class => ContactoPolicy::class,
        User::class => UserPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('update', [\App\Policies\OperatorPolicy::class, 'update']);

        Gate::define('manage-zone', function ($user, $zone) {
            return $user->role === 'admin' || $user->zona_id === $zone->id;
        });

        Gate::define('make-outgoing-call', function ($user, $patient) {
            if ($user->role === 'admin') return true;
            return $user->zones->contains($patient->zone_id);
        });

        Gate::define('manage-patient', function ($user, $patient) {
            if ($user->role === 'admin') return true;
            return $user->zones->contains($patient->zone_id);
        });
    }
}
