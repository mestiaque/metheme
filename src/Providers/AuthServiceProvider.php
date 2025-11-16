<?php

namespace Encodex\Metheme\Providers;

use Encodex\Metheme\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Container\Attributes\Log;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [

    ];

    public function boot(): void
    {
        $this->registerPolicies();
        Gate::before(function (?User $user, string $ability) {
            if ($user && $user->hasPermission($ability)) {
                return true;
            }

            return null;
        });
    }
}
