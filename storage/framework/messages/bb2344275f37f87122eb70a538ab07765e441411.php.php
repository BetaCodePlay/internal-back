<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Broadcast::routes(['middleware' => ['web']]);

        //require base_path('routes/channels.php');
        $this->mapWebRoutes();
    }

    protected function mapWebRoutes()
    {
        Route::group(['middleware' => 'web'], function () {
            $this->getRoutes(base_path('routes/channels/'));
        });
    }

    public function getRoutes($dir)
    {
        if ($dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {
                if (!is_dir($dir . $file) && $file != "." && $file != "..") {
                    require $dir . $file;
                } elseif ($file != "." && $file != "..") {
                    $this->getRoutes($dir . $file . '/');
                }
            }
            closedir($dh);
        }
    }

}
