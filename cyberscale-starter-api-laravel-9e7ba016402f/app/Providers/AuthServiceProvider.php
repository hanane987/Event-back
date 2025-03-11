<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Event;
use App\Models\Category;
use App\Models\Booking;
use App\Policies\UserPolicy;
use App\Policies\EventPolicy;
use App\Policies\CategoryPolicy;
use App\Policies\BookingPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * 
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Event::class => EventPolicy::class,
        Category::class => CategoryPolicy::class,
        Booking::class => BookingPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        
        Gate::define('viewDashboard', function (User $user) {
            return $user->isAdmin();
        });
    }
}

