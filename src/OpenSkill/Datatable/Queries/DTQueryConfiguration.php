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
     * @param string|int $drawCall
     * @param int $start
     * @param int $length
     */
    public function __construct(
        $drawCall,
        $start,
        $length
    )
    {
        $this->drawCall = $drawCall;
        $this->start = $start;
        $this->length = $length;
    }

    /**
     * @var string $drawCall
     *
     * Most data tables will send a drawCall with the request to make sure that the data
     * is not cached.
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


    public function drawCall()
    {
        return $this->drawCall;
    }

    public function start()
    {
        return $this->start;
    }

    public function length()
    {
        return $this->length;
    }

}