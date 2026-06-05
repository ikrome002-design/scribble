<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    protected $namespace = 'App\Http\Controllers';

    protected $namespacePro = 'App\Http\Controllers\Client\Pro';
    protected $namespaceProAdmin = 'App\Http\Controllers\Admin\Pro';
    protected $namespaceStaff = 'App\Http\Controllers\Staff';
    protected $namespaceApi = 'App\Http\Controllers\Api';

    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */

    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {

            //staff
            Route::domain('staff.' . env('APP_DOMAIN'))
                ->middleware('web')
                ->namespace($this->namespaceStaff)
                ->group(base_path('routes/staff.php'));

            //pro-admin    
            Route::domain('admin-pro.' . env('APP_DOMAIN'))
                ->middleware('web')
                ->namespace($this->namespaceProAdmin)
                ->group(base_path('routes/admin-pro.php'));

            //admin
            Route::domain('admin.' . env('APP_DOMAIN'))
                ->middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/admin.php'));

            //pro client
            Route::domain('pro.' . env('APP_DOMAIN'))
                ->middleware('web')
                ->namespace($this->namespacePro)
                ->group(base_path('routes/pro.php'));


            //api
            Route::domain('api.' . env('APP_URL'))
                ->middleware('api')
                ->namespace($this->namespaceApi)
                ->group(base_path('routes/api.php'));

            //client and other common
            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));
        });
    }
    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        // RateLimiter::for('api', function (Request $request) {
        //     return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        // });
    }
}
