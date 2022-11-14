<?php

namespace Dotworkers\Store;

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
            __DIR__ . '/config/store.php' => config_path('store.php')
        ]);
    }
}
