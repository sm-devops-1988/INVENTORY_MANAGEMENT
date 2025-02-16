<?php
namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * La route du "home" pour l'application.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Définir les routes pour l'application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();
        $this->mapWebRoutes();
    }

    /**
     * Définir les routes de l'API pour l'application.
     *
     * @return void
     */
    protected function mapApiRoutes()
{
    Route::prefix('api')
         ->middleware('api')
         ->namespace('App\Http\Controllers\API')  // Assurez-vous que le namespace est correct
         ->group(base_path('routes/api.php'));
}
    

    /**
     * Définir les routes Web pour l'application.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }
}

