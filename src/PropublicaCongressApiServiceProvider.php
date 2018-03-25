<?php declare(strict_types=1);

namespace Kirkster\PropublicaCongressApi;

use Illuminate\Support\ServiceProvider;
use Kirkaracha\PropublicaCongressApi\PropublicaCongressApi;

class PropublicaCongressApiServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/propublica-congress-api.php' => config_path('propublica-congress-api.php'),
        ]);
    }

    /**
     * Register the application services
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(PropublicaCongressApi::class, function () {
            return new PropublicaCongressApi();
        });

        $this->app->alias(PropublicaCongressApi::class, 'propublica-congress-api');

        $this->mergeConfigFrom(
            __DIR__ . '/config/propublica-congress-api.php', 'propublica-congress-api'
        );
    }
}
