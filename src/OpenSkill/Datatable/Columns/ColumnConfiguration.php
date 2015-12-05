<?php

namespace OpenSkill\Datatable\Columns;

use OpenSkill\Datatable\Columns\Orderable\Orderable;
use OpenSkill\Datatable\Columns\Searchable\Searchable;

/**
 * Class ColumnConfiguration
 * @package OpenSkill\Datatable\Columns
 *
 * The ColumnConfiguration is used to describe a column on the datatable. It contains all possible configuration options
 * so the data can be evaluated as well as the views can create a javascript representation of this configuration.
 */
class ColumnConfiguration
{

    /**
     * @var string The internal name of the column configuration
     */
    private $name;

    /**
     * @var Searchable Determines if the column can be searched on or not
     */
    private $searchable;

    /**
     * @var Orderable Determines if the column can be ordered on or not
     */
    private $orderable;

    /**
     * @var callable The function the user defines that should be called when the value of the columns should be calculated
     */
    private $callable;

    /**
     * ColumnConfiguration constructor.
     * As the class is immutable, all properties have to be set here
     *
     * @param string $name The internal name of the column configuration
     * @param callable $callable the function to call when the value should be calculated
     * @param Searchable $isSearchable If the column should be searchable
     * @param Orderable $isOrderable If the column should be orderable
     */
    public function __construct($name, $callable, Searchable $isSearchable, Orderable $isOrderable)
    {
        $this->name = $name;
        $this->callable = $callable;
        $this->searchable = $isSearchable;
        $this->orderable = $isOrderable;
    }

    /**
     * Will return the searchable column configuration
     *
     * @return Searchable
     */
    public function getSearch()
    {
        return $this->searchable;
    }

    /**
     * Will return the orderable column configuration
     *
     * @return Orderable
     */
    public function getOrder()
    {
        return $this->orderable;
    }

    /**
     * Will return the internal name of this column configuration
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Will return the function that will be executed upon calculation.
     *
     * @return callable
     */
    public function getCallable()
    {
        return $this->callable;
    }
}