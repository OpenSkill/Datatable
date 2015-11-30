<?php

namespace OpenSkill\Datatable\Versions;


use Illuminate\Http\Request;
use OpenSkill\Datatable\Queries\Parser\Datatable19QueryParser;
use OpenSkill\Datatable\Queries\Parser\QueryParser;
use OpenSkill\Datatable\Responses\Datatable19ResponseCreator;
use OpenSkill\Datatable\Responses\ResponseCreator;
use OpenSkill\Datatable\Views\Datatable19ViewCreator;
use OpenSkill\Datatable\Views\ViewCreator;

/**
 * Class Datatable19Version
 * @package OpenSkill\Datatable\Versions
 *
 * Version that supports the 1.9 version of datatables
 * http://legacy.datatables.net/index
 *
 */
class Datatable19Version extends Version
{
    /** @var QueryParser  */
    private $queryParser;

    /** @var ResponseCreator */
    private $responseCreator;

    /** @var  ViewCreator */
    private $viewCreator;

    /**
     * Datatable19Version constructor.
     *
     * @param Request $request The current request
     * @param QueryParser$queryParser a custom subclass for the query parser
     * @param ResponseCreator $responseCreator a custom subclass for the response
     * @param ViewCreator $viewCreator a custom subclass for the view
     */
    public function __construct(
        Request $request,
        QueryParser $queryParser = null,
        ResponseCreator $responseCreator = null,
        ViewCreator $viewCreator = null
    )
    {
        parent::__construct($request);
        if(is_null($queryParser)) {
            $this->queryParser = new Datatable19QueryParser($request);
        } else {
            $this->queryParser = $queryParser;
        }

        if(is_null($responseCreator)) {
            $this->responseCreator = new Datatable19ResponseCreator();
        } else {
            $this->responseCreator= $responseCreator;
        }

        if(is_null($viewCreator)) {
            $this->viewCreator = new Datatable19ViewCreator();
        } else {
            $this->viewCreator = $viewCreator;
        }
    }

    /**
     * Will get the QueryParser that is used to support this version of the data table
     *
     * @return QueryParser
     */
    public function queryParser()
    {
        return $this->queryParser;
    }

    /**
     * Will return the ResponseCreator that is used to support this version of the data table
     *
     * @return ResponseCreator
     */
    public function responseCreator()
    {
        return $this->responseCreator;
    }

    /**
     * Will return the ViewCreator that is used to support this version of the data table
     *
     * @return ViewCreator
     */
    public function viewCreator()
    {
        return $this->viewCreator;
    }
}