<?php

namespace OpenSkill\Datatable;

use App;
use Illuminate\Support\ServiceProvider;
use OpenSkill\Datatable\Versions\Datatable19Version;
use OpenSkill\Datatable\Versions\VersionEngine;
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

        $this->app->singleton("datatable", function () use ($requestStack) {
            return new Datatable(
                new VersionEngine([new Datatable19Version($requestStack)])
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
            'DT19Version',
            'DT110Version',
            'dt.query.versions'
        ];
    }
}
