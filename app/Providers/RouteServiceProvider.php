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
     * The path to the "home" route for your application.
     *
     * @var string
     */
    public const HOME = '/home';

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
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
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
        Route::prefix('api/authentication')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api/authentication/api.php'));
        Route::prefix('api/attendance')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api/attendance/api.php'));
        Route::prefix('api/raffle')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api/raffle/api.php'));
        Route::prefix('api/job_board')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api/job_board/api.php'));
        Route::prefix('api/cecy')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api/cecy/api.php'));
        Route::prefix('api/web')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api/web/api.php'));
        Route::prefix('api/ignug')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api/ignug/api.php'));
        Route::prefix('api/v7')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api/v7/api.php'));
        Route::prefix('api/v8')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api/v8/api.php'));
        Route::prefix('api/v9')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api/v9/api.php'));
        Route::prefix('api/v10')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api/v10/api.php'));
        Route::prefix('api/v11')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api/v11/api.php'));
    }
}
