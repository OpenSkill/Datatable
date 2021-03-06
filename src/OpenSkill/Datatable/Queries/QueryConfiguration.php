<?php

namespace OpenSkill\Datatable\Queries;

use OpenSkill\Datatable\Columns\ColumnOrder;
use OpenSkill\Datatable\Columns\ColumnSearch;

/**
 * Class DTQueryConfiguration
 * @package OpenSkill\Datatable\Interfaces
 *
 * This class contains all parameters that the frontend queries from the backend.
 * So this class contains the parsed query parameters, abstracted away form the data table frontend
 */
class QueryConfiguration
{

    /**
     * DTQueryConfiguration constructor.
     * @param string $drawCall the frontend draw call forwarded to the backend
     * @param int $start the start index of the data entries
     * @param int $length the amount of items that should be returned
     * @param string $searchValue the global search value that should be searched for
     * @param bool $searchRegex true if the search value should be evaluated as a regex expression
     * @param ColumnSearch[] $columnSearches all search values for the individual columns if available and allowed
     * @param ColumnOrder[] $columnOrders the order for each column if available and allowed
     */
    public function __construct(
        $drawCall,
        $start,
        $length,
        $searchValue,
        $searchRegex,
        array $columnSearches,
        array $columnOrders
    ) {
        $this->drawCall = $drawCall;
        $this->start = $start;
        $this->length = $length;
        $this->searchValue = $searchValue;
        $this->searchRegex = $searchRegex;
        $this->searchColumns = $columnSearches;
        $this->orderColumns = $columnOrders;
    }

    /**
     * Most data tables will send a drawCall with the request to make sure that the data
     * is not cached.
     *
     * @var string $drawCall
     */
    protected $drawCall;

    /**
     * @var string The string we are searching for (note: for searchColumns)
     */
    protected $searchValue = null;

    /**
     * @var ColumnSearch[] the columns that we are searching, the content that has been put in.
     * It is a map with the following structure: ['id' => column]
     */
    protected $searchColumns = [];

    /**
     * @var bool the search is a regular expression
     */
    protected $searchRegex = true;

    /**
     * @var ColumnOrder[] a list of the columns we are sorting by, with their direction, this is just a list of objects
     */
    protected $orderColumns = [];

    /**
     * @var int which result to start from
     */
    protected $start;

    /**
     * @var int the length of the wished result.
     */
    protected $length;

    /**
     * @return string will return the draw value that the frontend send to the backend
     */
    public function drawCall()
    {
        return $this->drawCall;
    }

    /**
     * @return int will return the value that the results should start with
     */
    public function start()
    {
        return $this->start;
    }

    /**
     * @return int returns the amount of items the frontend requested
     */
    public function length()
    {
        return $this->length;
    }

    /**
     * @return string will return the value that the frontend wants to search for globally
     */
    public function searchValue()
    {
        return $this->searchValue;
    }

    /**
     * @return bool will return if the current search value should be used as a regex
     */
    public function isGlobalRegex()
    {
        return $this->searchRegex;
    }

    /**
     * @return ColumnSearch[]
     */
    public function searchColumns()
    {
        return $this->searchColumns;
    }

    /**
     * @return ColumnOrder[]
     */
    public function orderColumns()
    {
        return $this->orderColumns;
    }

    /**
     * @return bool true if global search is enabled
     */
    public function isGlobalSearch()
    {
        return !empty($this->searchValue);
    }

    /**
     * @param string $columnName the name of the column
     * @return bool
     */
    public function hasSearchColumn($columnName)
    {
        return array_key_exists($columnName, $this->searchColumns);
    }

    public function hasOrderColumn()
    {
        return !empty($this->orderColumns);
    }

    /**
     * @return bool will return true if the query asks for a column search, false otherwise
     */
    public function isColumnSearch()
    {
        return count($this->searchColumns) != 0;
    }
}