<?php

namespace Dotworkers\Configurations;

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
            __DIR__ . '/config/configurations.php' => config_path('configurations.php'),
        ]);
    }
}
