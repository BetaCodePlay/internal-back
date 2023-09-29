<?php

namespace App\Providers;

use Dotworkers\Security\Security;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerPolicies();
        // $arraySession = session()->has('permissions')?session('permissions'):session('permissions');
        // use ($arraySession)
        Gate::define('access', function ($user, $permission) {
            return Security::checkPermissions($permission, session()->get('permissions', []));
        });
    }
}
