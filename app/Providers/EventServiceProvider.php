<?php

namespace App\Providers;

use App\Models\Detachment;
use App\Models\User;
use App\Observers\DetachmentObserver;
use App\Observers\UserObserver;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{

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

    // --- MANUALLY REGISTER THE OBSERVER ---
    // Manually register your observers here
    User::observe(UserObserver::class);
    Detachment::observe(DetachmentObserver::class);
  }
}
