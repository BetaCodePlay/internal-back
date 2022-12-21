<?php

namespace Dotworkers\Sessions;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/sessions.php' => config_path('sessions.php'),
        ]);
    }
}
