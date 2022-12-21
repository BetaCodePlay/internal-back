<?php

namespace Dotworkers\Wallet;

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
            __DIR__ . '/config/wallet.php' => config_path('wallet.php'),
        ]);
    }
}
