<?php

namespace App\Providers;

use App\Models\Call;
use App\Models\Patient;
use App\Models\Zone;
use App\Models\Alert;
use App\Models\Contact;
use App\Models\Operator;
use App\Policies\CallPolicy;
use App\Policies\PatientPolicy;
use App\Policies\ZonePolicy;
use App\Policies\AlertPolicy;
use App\Policies\ContactPolicy;
use App\Policies\OperatorPolicy;
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
        Patient::class => PatientPolicy::class,
        Call::class => CallPolicy::class,
        Zone::class => ZonePolicy::class,
        Alert::class => AlertPolicy::class,
        Contact::class => ContactPolicy::class,
        Operator::class => OperatorPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Gate para gestionar zonas
        Gate::define('manage-zone', function ($user, $zone) {
            return $user->role === 'admin' || $user->zones->contains($zone->id);
        });

        // Gate para llamadas salientes
        Gate::define('make-outgoing-call', function ($user, $patient) {
            if ($user->role === 'admin') return true;
            return $user->zones->contains($patient->zone_id);
        });

        // Gate para gestión de pacientes
        Gate::define('manage-patient', function ($user, $patient) {
            if ($user->role === 'admin') return true;
            return $user->zones->contains($patient->zone_id);
        });
    }
}
