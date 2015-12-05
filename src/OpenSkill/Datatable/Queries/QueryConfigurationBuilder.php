<?php

namespace OpenSkill\Datatable\Queries;

use OpenSkill\Datatable\Columns\ColumnOrder;

class QueryConfigurationBuilder
{

    /** @var string */
    protected $drawCall = 1;

    /** @var int */
    protected $start = 1;

    /** @var int */
    protected $length = 10;

    /** @var string */
    protected $searchValue = null;

    /** @var bool */
    protected $searchRegex = false;

    /** @var array */
    protected $columSearches = [];

    /** @var array */
    protected $columnOrders = [];

    /**
     * DTQueryConfigurationBuilder constructor, private by default, so new instances are created using the builder
     * pattern
     */
    private function __construct()
    {
    }

    /**
     * Will create a new QueryConfigurationBuilder internally and acts as static builder method
     * @return QueryConfigurationBuilder
     */
    public static function create()
    {
        return new QueryCOnfigurationBuilder();
    }

    /**
     * Will set the drawCall parameter send by the frontend.
     * @param string|int $drawCall The draw call parameter
     * @return $this
     */
    public function drawCall($drawCall)
    {
        if (!is_string($drawCall) && !is_numeric($drawCall)) {
            throw new \InvalidArgumentException('$drawCall needs to be a string or numeric');
        }
        $this->drawCall = $drawCall;
        return $this;
    }

    /**
     * Will set the start parameter which indicates how many items should be skipped at the start
     * @param int $start
     * @return $this
     */
    public function start($start)
    {
        if (!is_numeric($start)) {
            throw new \InvalidArgumentException('$start needs to be numeric');
        }
        $this->start = $start;
        return $this;
    }

    /**
     * Will set the length parameter which indicates how many items should be returned by this request.
     * @param int $length
     * @return $this
     */
    public function length($length)
    {
        if (!is_numeric($length)) {
            throw new \InvalidArgumentException('$length needs to be numeric');
        }
        $this->length = $length;
        return $this;
    }

    /**
     * Will set the search value the frontend send that should be used for the global search
     * @param string $searchValue
     * @return $this
     */
    public function searchValue($searchValue)
    {
        if (!is_string($searchValue)) {
            throw new \InvalidArgumentException('$searchValue needs to be a string');
        }
        $this->searchValue = $searchValue;
        return $this;
    }

    /**
     * Will indicate if the global search value should be used as a regular expression
     * @param bool $searchRegex
     * @return $this
     */
    public function searchRegex($searchRegex)
    {
        if (!is_bool($searchRegex)) {
            throw new \InvalidArgumentException('$searchRegex needs to be a boolean');
        }
        $this->searchRegex = $searchRegex;
        return $this;
    }

    /**
     * Will add the given search value to the given column which indicates that the frontend wants to search on the
     * given column for the given value
     * @param string $columnName The name of the column that will be searched
     * @param string $searchValue The value to search for
     * @return $this
     */
    public function columnSearch($columnName, $searchValue)
    {
        if (!is_string($searchValue)) {
            throw new \InvalidArgumentException('$searchValue needs to be a string');
        }
        $this->columSearches[$columnName] = $searchValue;
        return $this;
    }

    /**
     * Will set the ordering of the column to the given direction if possible
     * @param string $columnName The column name that should be ordered
     * @param string $orderDirection the direction that the column should be ordered by
     * @return $this
     */
    public function columnOrder($columnName, $orderDirection)
    {
        if (!is_string($orderDirection)) {
            throw new \InvalidArgumentException('$orderDirection "' . $orderDirection . '" needs to be a string');
        }
        $isAscOrdering = $orderDirection === "asc" ? true : false;
        $this->columnOrders[] = new ColumnOrder($columnName, $isAscOrdering);
        return $this;
    }

    /**
     * Will build the final QueryConfiguration that will be used later in the process pipeline
     * @return QueryConfiguration
     */
    public function build()
    {
        return new QueryConfiguration(
            $this->drawCall,
            $this->start,
            $this->length,
            $this->searchValue,
            $this->searchRegex,
            $this->columSearches,
            $this->columnOrders
        );
    }


}