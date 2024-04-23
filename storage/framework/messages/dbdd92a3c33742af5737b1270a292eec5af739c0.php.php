<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Get routes
     *
     * @param string $dir Routes directory
     */
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

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::group(['middleware' => 'web', 'namespace' => $this->namespace], function ($router) {
            $this->getRoutes(base_path('routes/web/'));
        });
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::group(['prefix' => 'api', 'middleware' => 'api', 'namespace' => $this->namespace], function ($router) {
            $this->getRoutes(base_path('routes/api/'));
        });
    }

    protected function mapChannelsRoutes()
    {
        Route::group(['middleware' => 'web', 'namespace' => $this->namespace], function ($router) {
            $this->getRoutes(base_path('routes/channels/'));
        });
    }
}
