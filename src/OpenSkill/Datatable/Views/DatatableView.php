<?php

namespace OpenSkill\Datatable\Views;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use OpenSkill\Datatable\Columns\ColumnConfiguration;
use OpenSkill\Datatable\Versions\Version;

/**
 * Class DatatableView
 * @package OpenSkill\Datatable\Views
 *
 * The class is used to prepare the view with the table and the javascript of the current version.
 */
class DatatableView
{

    /**
     * @var ColumnConfiguration[] the column configuration if any
     */
    private $columnConfigurations;

    /**
     * Indicates if the columnConfigurations should be reset on a call to columns.
     */
    private $resetColumns = true;
    /**
     * @var null|string
     */
    private $tableView;
    /**
     * @var null|string
     */
    private $scriptView;
    /**
     * @var null|Version
     */
    private $version;

    /**
     * @var Factory
     */
    private $viewFactory;

    /**
     * @var bool true if the columns are also printed as headers on the table, false otherwise
     */
    private $printHeaders;

    /**
     * DatatableView constructor, will take a view as a string if a custom one should be used. will also take the
     * column configurations to provide out of the box headers for the view.
     * If no columns are given the user must provide them before building the view.
     * @param string|null $tableView the name of the view that should be rendered for the table
     * @param string|null $scriptView the name of the view that should be rendered for the script
     * @param Version|null $version The version that supports the current request
     * @param Factory $viewFactory The factory used to render the views
     * @param array $columnConfiguration The columnConfiguration of the the server side if available
     */
    public function __construct(
        $tableView = null,
        $scriptView = null,
        Version $version = null,
        Factory $viewFactory,
        array $columnConfiguration = []
    ) {
        $this->columnConfigurations = $columnConfiguration;
        $this->tableView = $tableView;
        $this->scriptView = $scriptView;
        $this->version = $version;
        $this->viewFactory = $viewFactory;
    }

    /**
     * Indicates that the current columns should have a header on the table
     */
    public function headers() {
        $this->printHeaders = true;
    }

    /**
     * Will set the columns for the view
     */
    public function columns()
    {
        if ($this->resetColumns) {
            $this->columnConfigurations = [];
            $this->resetColumns = false;
        }
    }

    /**
     * Will render the table
     *
     * @return View the view that represents the table
     */
    public function table()
    {
        if (empty($this->columnConfigurations)) {
            throw new \InvalidArgumentException("There are no columns defined");
        }
        return $this->viewFactory->make($this->tableView);
    }

    /**
     * Will render the javascript for the table
     *
     * @return View the view that represents the script
     */
    public function script()
    {
        if (empty($this->columnConfigurations)) {
            throw new \InvalidArgumentException("There are no columns defined");
        }
        return $this->viewFactory->make($this->tableView);
    }
}