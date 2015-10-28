<?php

namespace Chumper\Datatable\Composers;

use Chumper\Datatable\Columns\ColumnBuilder;
use Chumper\Datatable\Columns\ColumnConfiguration;
use Chumper\Datatable\Columns\ColumnConfigurationBuilder;
use Chumper\Datatable\Providers\DTProvider;

/**
 * Class DTDataComposer
 * @package Chumper\Datatable\Composers
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
     * Will create a new datatable composer instance with the given provider
     * @param DTProvider $provider the provider that will process the underlying data
     */
    public function __construct(DTProvider $provider)
    {
        $this->provider = $provider;
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
     * Will create a new ColumnConfiguration with all defaults but allows overriding all properties through the method.
     *
     * @param string $name The name of the configuration, required for the configuration
     * @param callable $callable The function to execute, defaults to null which means the default will be set.
     * @param bool $searchable If the column should be searchable or not
     * @param bool $orderable If the column should be orderable or not
     * @return DTDataComposer
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
}