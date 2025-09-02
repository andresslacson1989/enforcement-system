<?php

namespace App\Providers;

use App\Listeners\UpdatePrimaryRoleOnRoleSync;
use App\Models\Detachment;
use App\Models\User;
use App\Observers\DetachmentObserver;
use App\Observers\UserObserver;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Spatie\Permission\Events\RoleAttached;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        // Add this entry
        RoleAttached::class => [
            UpdatePrimaryRoleOnRoleSync::class,
        ],
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        parent::boot();

        // Manually register observers
        User::observe(UserObserver::class);
        Detachment::observe(DetachmentObserver::class);
    }
}
