<?php

namespace OpenSkill\Datatable\Composers;

use OpenSkill\Datatable\Columns\ColumnConfiguration;
use OpenSkill\Datatable\Columns\ColumnConfigurationBuilder;
use OpenSkill\Datatable\Columns\Orderable\Orderable;
use OpenSkill\Datatable\Columns\Searchable\Searchable;
use OpenSkill\Datatable\DatatableService;
use OpenSkill\Datatable\Providers\Provider;
use OpenSkill\Datatable\Versions\VersionEngine;

/**
 * Class ColumnComposer
 * @package OpenSkill\Datatable\Composers
 *
 * The composer is responsible to collect all column configuration as well as view configurations and to pass them
 * to the DTProvider when the data needs to be collected.
 */
class ColumnComposer
{
    /**
     * @var Provider The Provider for the underlying data.
     */
    private $provider;

    /**
     * @var VersionEngine The version engine that will parse the request parameter.
     */
    private $version;

    /**
     * @var ColumnConfiguration[] An array of the configurations of the columns
     */
    private $columnConfiguration = [];

    /**
     * Will create a new datatable composer instance with the given provider
     * @param Provider $provider the provider that will process the underlying data
     * @param VersionEngine $versionEngine The version engine to handle the request data
     */
    public function __construct(Provider $provider, VersionEngine $versionEngine)
    {
        $this->provider = $provider;
        $this->version = $versionEngine;
    }

    /**
     * Will return the Provider for the underlying data.
     * @return Provider
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * Will return the internal column configurations that are registered with the current composer.
     *
     * @return ColumnConfiguration[]
     */
    public function getColumnConfiguration()
    {
        return $this->columnConfiguration;
    }

    /**
     * Will create a new ColumnConfiguration with all defaults but allows overriding of all properties through the method.
     *
     * @param string $name The name of the configuration, required for the configuration
     * @param callable $callable The function to execute, defaults to null which means the default will be set.
     * @param Searchable $searchable If the column should be searchable or not
     * @param Orderable $orderable If the column should be orderable or not
     * @return $this
     */
    public function column($name, callable $callable = null, Searchable $searchable = null, Orderable $orderable = null)
    {
        /**
         * @var ColumnConfigurationBuilder
         */
        $config = null;

        if (is_string($name)) {
            $config = ColumnConfigurationBuilder::create()
                ->name($name);
        } else {
            throw new \InvalidArgumentException('$name must be a string');
        }

        if (!is_null($callable) && is_callable($callable)) {
            $config->withCallable($callable);
        }

        if (is_null($searchable)) {
            $config->searchable(Searchable::NORMAL());
        }

        if (is_null($orderable)) {
            $config->orderable(Orderable::BOTH());
        }

        $this->columnConfiguration[] = $config->build();
        return $this;
    }

    /**
     * This method will add the given ColumnConfiguration to the composer.
     *
     * @param ColumnConfiguration $configuration the configuration to add to the composer
     *
     * @return $this
     */
    public function add(ColumnConfiguration $configuration)
    {
        $this->columnConfiguration[] = $configuration;
        return $this;
    }

    /**
     * @return DatatableService Will return the fully built DatatableService that will contain the ColumnConfiguration
     */
    public function build() {
        return new DatatableService($this->version);
    }

}