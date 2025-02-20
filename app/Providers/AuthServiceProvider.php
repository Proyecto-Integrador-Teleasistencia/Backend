<?php

namespace App\Providers;

use App\Models\Call;
use App\Models\Patient;
use App\Policies\CallPolicy;
use App\Policies\PatientPolicy;
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
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Define gates adicionales si son necesarios
        Gate::define('manage-zone', function ($user, $zone) {
            return $user->role === 'admin' || $user->zones->contains($zone->id);
        });
    }
}
