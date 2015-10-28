<?php

namespace OpenSKill\Datatable\Interfaces;

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
     * @var bool do we need to search on columns, or just order & filter?
     */
    private $searchColumns = false;

    /**
     * @var bool are we using a plugin to search individual plugins
     */
    protected $searchIndividualColumns = true;

    /**
     * @var string The string we are searching for (note: for searchColumns)
     */
    protected $searchString = '';

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
    protected $start = 0;

    /**
     * @var int the limit of the result.
     */
    protected $limit = 0;

}