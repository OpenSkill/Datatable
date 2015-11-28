<?php

namespace OpenSkill\Datatable\Queries;

/**
 * Class DTQueryConfiguration
 * @package OpenSkill\Datatable\Interfaces
 *
 * This class contains all parameters that the frontend queries from the backend.
 * So this class contains the parsed query parameters, abstracted away form datatables 1.9 and 1.10
 */
class DTQueryConfiguration
{

    /**
     * DTQueryConfiguration constructor.
     * @param string $drawCall the frontend draw call forwarded to the backend
     * @param int $start the start index of the data entries
     * @param int $length the amount of items that should be returned
     * @param string $searchValue the global search value that should be searched for
     * @param bool $searchRegex true if the search value should be evaluated as a regex expression
     */
    public function __construct(
        $drawCall,
        $start,
        $length,
        $searchValue,
        $searchRegex
    )
    {
        $this->drawCall = $drawCall;
        $this->start = $start;
        $this->length = $length;
        $this->searchValue = $searchValue;
        $this->searchRegex = $searchRegex;
    }

    /**
     * Most data tables will send a drawCall with the request to make sure that the data
     * is not cached.
     *
     * @var string $drawCall
     */
    protected $drawCall;

    /**
     * @var bool are we using a plugin to search individual plugins
     */
    protected $searchIndividualColumns = true;

    /**
     * @var string The string we are searching for (note: for searchColumns)
     */
    protected $searchValue = null;

    /**
     * @var array the columns that we are searching, the content that has been put in
     */
    protected $searchColumn = [];

    /**
     * @var bool the search is a regular expression
     */
    protected $searchRegex = true;

    /**
     * @var int the number of columns we are showing in the datatable
     */
    protected $numberOfColumns = 0;

    /**
     * @var array a list of all the columns we are showing
     */
    protected $columns = [];

    /**
     * @var array a list of the columns we are sorting by, with their direction
     */
    /* [    [ 'id' => 'desc' ], [ 'name', 'asc' ]    ] */
    protected $order = [];

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

}