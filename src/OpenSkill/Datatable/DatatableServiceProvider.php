<?php

namespace OpenSkill\Datatable;

use App;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use OpenSkill\Datatable\Versions\Datatable110Version;
use OpenSkill\Datatable\Versions\Datatable19Version;
use OpenSkill\Datatable\Versions\VersionEngine;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class DatatableServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        /** @var RequestStack $requestStack */
        $requestStack = $this->app->make('Symfony\Component\HttpFoundation\RequestStack');
        if ($requestStack->getCurrentRequest() == null) {
            $requestStack->push($this->app->make('request'));
        }

        $this->app->singleton("datatable", function (Application $app) use ($requestStack) {
            $dt = new Datatable19Version($requestStack);
            $dt2 = new Datatable110Version($requestStack);

            return new Datatable(
                new VersionEngine([$dt2, $dt]),
                $app->make('Illuminate\Contracts\View\Factory'),
                $app->make('Illuminate\Contracts\Config\Repository')
            );
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'datatable',
        ];
    }

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // load views
        $this->loadViewsFrom(__DIR__.'/../../views', 'datatable');

        // publish views if the user wants to
        $this->publishes([
            __DIR__.'/../../views' => base_path('resources/views/vendor/datatable'),
        ]);

        $this->mergeConfigFrom(
            __DIR__.'/../../config/datatable.php', 'datatable'
        );

        // publish the configs if the user wants to
        $this->publishes([
            __DIR__.'/../../config/datatable.php' => config_path('datatable.php'),
        ]);
    }


}
