<?php

namespace OpenSkill\Datatable;

use Illuminate\Support\ServiceProvider;
use OpenSkill\Datatable\Versions\DTVersionEngine;

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

        $this->app->bind('DT19Version', 'OpenSkill\Datatable\Versions\Datatable19Version');
        $this->app->bind('dt.default.version', 'OpenSkill\Datatable\Versions\Datatable110Version');

        $this->app->tag(['DT19Version', 'dt.default.version'], 'dt.query.versions');

        $this->app->singleton("datatable", function ($app) {
            return new Datatable(
                new DTVersionEngine(
                    $app->make('request'),
                    $app->tagged('dt.query.versions')
                )
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
            'dt.default.version'
        ];
    }
}
