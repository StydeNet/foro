<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

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
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapPublicRoutes();

        $this->mapGuestRoutes();

        $this->mapAuthRoutes();
    }

    protected function mapPublicRoutes()
    {
        Route::group([
            'middleware' => 'web',
            'namespace' => $this->namespace,
        ], function ($router) {
            require base_path('routes/public.php');
        });
    }

    protected function mapGuestRoutes()
    {
        Route::group([
            'middleware' => ['web', 'guest'],
            'namespace' => $this->namespace,
        ], function ($router) {
            require base_path('routes/guest.php');
        });
    }

    protected function mapAuthRoutes()
    {
        Route::group([
            'middleware' => ['web', 'auth'],
            'namespace' => $this->namespace,
        ], function ($router) {
            require base_path('routes/auth.php');
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
        Route::group([
            'middleware' => 'api',
            'namespace' => $this->namespace,
            'prefix' => 'api',
        ], function ($router) {
            require base_path('routes/api.php');
        });
    }
}
