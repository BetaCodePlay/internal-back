<?php
namespace Dotworkers\Bonus;

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
            __DIR__ . '/config/bonus.php' => config_path('bonus.php'),
        ]);
    }
}