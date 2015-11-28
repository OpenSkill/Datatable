<?php

namespace OpenSkill\Datatable\Versions;


use OpenSkill\Datatable\Queries\Datatable19QueryParser;
use OpenSkill\Datatable\Queries\QueryParser;
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
class Datatable19Version implements Version
{
    /** @var Datatable19QueryParser  */
    private $queryParser;

    /** @var Datatable19ResponseCreator  */
    private $responseCreator;

    /** @var  Datatable19ViewCreator */
    private $viewCreator;

    /**
     * Datatable19Version constructor.
     *
     * @param Datatable19QueryParser $queryParser a custom subclass for the query parser
     * @param Datatable19ResponseCreator $responseCreator a custom subclass for the response
     * @param Datatable19ViewCreator $viewCreator a custom subclass for the view
     */
    public function __construct(
        Datatable19QueryParser $queryParser = null,
        Datatable19ResponseCreator $responseCreator = null,
        Datatable19ViewCreator $viewCreator = null
    )
    {
        if(is_null($queryParser)) {
            $this->queryParser = new Datatable19QueryParser();
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