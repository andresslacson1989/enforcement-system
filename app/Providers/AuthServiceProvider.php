<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Models\Detachment' => 'App\Policies\DetachmentPolicy',
        'App\Models\User' => 'App\Policies\UserPolicy',
        'App\Models\FirstMonthPerformanceEvaluationForm' => 'App\Policies\FirstMonthPerformanceEvaluationFormPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

    }
}
