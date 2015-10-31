<?php

namespace OpenSkill\Datatable;

use Illuminate\Support\ServiceProvider;
use OpenSkill\Datatable\Query\DTQueryEngine;

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

        $this->app->bind('DT19Parser', 'OpenSkill\Datatable\Query\DT19QueryParser');
        $this->app->bind('DT110Parser', 'OpenSkill\Datatable\Query\DT110QueryParser');

        $this->app->tag(['DT19Parser', 'DT110Parser'], 'dt.query.parser');

        $this->app->singleton("datatable", function ($app) {
            return new Datatable(new DTQueryEngine($app->make('request'), $app->tagged('dt.query.parser')));
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
}
