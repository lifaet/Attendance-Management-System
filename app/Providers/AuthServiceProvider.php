<?php

namespace App\Providers;

use App\Models\ClassRoom;
use App\Policies\ClassRoomPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        ClassRoom::class => ClassRoomPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        Gate::before(function ($user, $ability) {
            return $user->role === 'admin' ? true : null;
        });
    }
}