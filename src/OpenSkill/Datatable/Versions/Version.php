<?php

namespace OpenSkill\Datatable\Versions;

use Illuminate\Http\Request;
use OpenSkill\Datatable\Queries\Parser\QueryParser;
use OpenSkill\Datatable\Responses\ResponseCreator;
use OpenSkill\Datatable\Views\ViewCreator;

/**
 * Interface DTVersion
 * @package OpenSkill\Datatable\Versions
 *
 * Base interface that is used to support different frontend views from this data table plugin
 */
abstract class Version
{

    /** @var Request */
    protected $request;

    /**
     * Version constructor.
     * @param Request $request The current request
     */
    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * Will get the QueryParser that is used to support this version of the data table
     *
     * @return QueryParser
     */
    public abstract function queryParser();

    /**
     * Will return the ResponseCreator that is used to support this version of the data table
     *
     * @return ResponseCreator
     */
    public abstract function responseCreator();

    /**
     * Will return the ViewCreator that is used to support this version of the data table
     *
     * @return ViewCreator
     */
    public abstract function viewCreator();
}