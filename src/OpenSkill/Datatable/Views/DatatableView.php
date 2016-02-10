<?php

namespace OpenSkill\Datatable\Views;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use OpenSkill\Datatable\Columns\ColumnConfiguration;

/**
 * Class DatatableView
 * @package OpenSkill\Datatable\Views
 *
 * The class is used to prepare the view with the table and the javascript of the current version.
 */
class DatatableView
{

    /** @var array the columns map with name -> label */
    private $columns;

    /** @var bool Indicates if the columnConfigurations should be reset on a call to columns. */
    private $resetColumns = true;

    /** @var string The view that should be used to render the table */
    private $tableView;

    /** @var string The view that should be used to render the script */
    private $scriptView;

    /** @var string The id that the table will get in the DOM. Used to create a fitting script for the table */
    private $tableId;

    /** @var array An array of options that should be noted in the script view of the table */
    private $scriptOptions = [];

    /** @var array An array of callback that should be noted in the script view of the table. Only differs in encoding */
    private $scriptCallbacks = [];

    /** @var Factory The factory responsible to render the view with the given data */
    private $viewFactory;

    /** @var bool true if the columns are also printed as headers on the table, false otherwise */
    private $printHeaders = false;

    /** @var Repository The repository responsible for the config value resolution */
    private $configRepository;

    /** @var string the URL for the endpoint */
    private $endpointURL = '/';

    /**
     * DatatableView constructor, will take a view as a string if a custom one should be used. will also take the
     * column configurations to provide out of the box headers for the view.
     * If no columns are given the user must provide them before building the view.
     * @param string|null $tableView the name of the view that should be rendered for the table
     * @param string|null $scriptView the name of the view that should be rendered for the script
     * @param Factory $viewFactory The factory used to render the views
     * @param Repository $configRepository The repository responsible for config resolution
     * @param ColumnConfiguration[] $columnConfiguration The columnConfiguration of the the server side if available
     */
    public function __construct(
        $tableView,
        $scriptView,
        Factory $viewFactory,
        Repository $configRepository,
        array $columnConfiguration = []
    ) {
        $this->tableView = $tableView;
        $this->scriptView = $scriptView;
        $this->viewFactory = $viewFactory;
        $this->configRepository = $configRepository;
        foreach ($columnConfiguration as $item) {
            $this->columns[$item->getName()] = $item->getName();
        }
        // set table id
        $this->id($this->configRepository->get('datatable.defaultTableId'));
    }

    /**
     * Will set a new id on the table.
     * @param string $tableId The new id that should be used for the DOM table
     * @return $this
     */
    public function id($tableId)
    {
        if (!is_string($tableId)) {
            throw new \InvalidArgumentException('$tableId should be a string');
        }
        $this->tableId = $tableId;
        return $this;
    }

    /**
     * @param string $name the name of the option
     * @param mixed $options an array of options
     * @return $this
     */
    public function option($name, $options)
    {
        $this->scriptOptions[$name] = $options;
        return $this;
    }

    /**
     * @param string $name the name of the callback function
     * @param string $callback the body of the callback function
     * @return $this
     */
    public function callback($name, $callback)
    {
        $this->scriptCallbacks[$name] = $callback;
        return $this;
    }

    /**
     * Indicates that the current columns should have a header on the table
     * @return $this
     */
    public function headers()
    {
        $this->printHeaders = true;
        return $this;
    }

    /**
     * Sets the endpoint URL that will be passed to templates when rendering html & scripts.
     * @param $endpoint_url
     * @return $this
     */
    public function endpoint($endpoint_url)
    {
        $this->endpointURL = $endpoint_url;
        return $this;
    }

    /**
     * Will set the columns for the view
     * @param string $columnName The name of the column
     * @param string $label The label for this column
     * @return $this
     */
    public function columns($columnName, $label = null)
    {
        if (!is_string($columnName)) {
            throw new \InvalidArgumentException('$columnName must be set');
        }

        if ($this->resetColumns) {
            $this->columns = [];
            $this->resetColumns = false;
        }
        if (is_null($label)) {
            $label = $columnName;
        }
        $this->columns[$columnName] = $label;
        return $this;
    }

    /**
     * Will render the table
     *
     * @return string the rendered view that represents the table
     */
    public function table()
    {
        if (empty($this->columns)) {
            throw new \InvalidArgumentException("There are no columns defined");
        }

        return $this->viewFactory
            ->make($this->tableView, [
                'columns' => $this->columns,
                'showHeaders' => $this->printHeaders,
                'id' => $this->tableId,
                'endpoint' => $this->endpointURL,
            ])
            ->render();
    }

    /**
     * Will render the javascript for the table
     *
     * @return string the rendered view that represents the script
     */
    public function script()
    {
        if (empty($this->columns)) {
            throw new \InvalidArgumentException("There are no columns defined");
        }
        return $this->viewFactory
            ->make($this->scriptView, [
                'id' => $this->tableId,
                'columns' => $this->columns,
                'options' => $this->scriptOptions,
                'callbacks' => $this->scriptCallbacks,
                'endpoint' => $this->endpointURL,
            ])
            ->render();
    }

    /**
     * Will render the table and directly the script after it. This is a shortcut for
     * {@link #table} followed by {@link script}
     * @return string Will return the rendered table and the rendered script as string
     */
    public function html()
    {
        return $this->table() . $this->script();
    }
}