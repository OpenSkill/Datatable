<?php

namespace OpenSkill\Datatable;

use App;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use OpenSkill\Datatable\Versions\VersionEngine;

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

        $this->app->singleton("datatable", function (Application $app) {
            return new Datatable(
                $app->make('request'),
                new VersionEngine($app->tagged('dt.query.versions'))
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