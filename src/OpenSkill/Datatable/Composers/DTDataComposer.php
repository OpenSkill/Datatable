<?php

namespace OpenSkill\Datatable\Composers;

use OpenSkill\Datatable\Columns\ColumnConfiguration;
use OpenSkill\Datatable\Columns\ColumnConfigurationBuilder;
use OpenSkill\Datatable\Query\DTQueryEngine;
use OpenSkill\Datatable\Providers\DTProvider;

/**
 * Class DTDataComposer
 * @package OpenSkill\Datatable\Composers
 *
 * The composer is responsible to collect all column configuration as well as view configurations and to pass them
 * to the DTProvider when the data needs to be collected.
 */
class DTDataComposer
{
    /**
     * @var DTProvider The Provider for the underlying data.
     */
    private $provider;

    /**
     * @var ColumnConfiguration[] An array of the configurations of the columns
     */
    private $columnConfiguration = [];

    /**
     * @var DTQueryEngine The engine that will parse the request and offers a DTQueryConfiguration
     */
    private $queryEngine;

    /**
     * Will create a new datatable composer instance with the given provider
     * @param DTProvider $provider the provider that will process the underlying data
     * @param DTQueryEngine $queryEngine The query engine that will parse the request and offers the query parameters
     */
    public function __construct(DTProvider $provider, DTQueryEngine $queryEngine)
    {
        $this->provider = $provider;
        $this->queryEngine = $queryEngine;
    }

    /**
     * Will return the Provider for the underlying data.
     * @return DTProvider
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
     * @param bool $searchable If the column should be searchable or not
     * @param bool $orderable If the column should be orderable or not
     * @return $this
     */
    public function column($name, callable $callable = null, $searchable = true, $orderable = true)
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

        if (is_bool($searchable)) {
            $config->searchable($searchable);
        } else {
            throw new \InvalidArgumentException('$searchable needs to be a boolean value');
        }

        if (is_bool($orderable)) {
            $config->orderable($orderable);
        } else {
            throw new \InvalidArgumentException('$orderable needs to be a boolean value');
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
     * Called when the current DTDataComposer should handle the request and return data.
     * This is a terminating operation
     */
    public function handleRequest()
    {
        // get the query configuration and pass it to the provider,
        // take the data and prepare it for display
    }


}