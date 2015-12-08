<?php

namespace OpenSkill\Datatable\Views;
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
     * DatatableView constructor, will take a view as a string if a custom one should be used. will also take the
     * column configurations to provide out of the box headers for the view.
     * If no columns are given the user must provide them before building the view.
     * @param array $columnConfiguration The columnConfiguration of the the server side if available
     * @param Version $version The version that supports the current request
     * @param string $view the name of the view that should be rendered
     */
    public function __construct($view = null, Version $version = null, array $columnConfiguration = [])
    {
        $this->columnConfigurations = $columnConfiguration;
    }

    /**
     * Will set the columns for the view
     * @param $asHeader
     */
    public function columns($asHeader = true, $names) {

    }

    /**
     * Will render the table
     */
    public function table() {
        if(empty($this->columnConfigurations)) {
            throw new \InvalidArgumentException("There are no columns defined");
        }
    }

    /**
     * Will render the javascript for the table
     */
    public function script() {
        if(empty($this->columnConfigurations)) {
            throw new \InvalidArgumentException("There are no columns defined");
        }
    }
}