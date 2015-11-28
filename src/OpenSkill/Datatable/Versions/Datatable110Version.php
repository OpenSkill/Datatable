<?php

namespace OpenSkill\Datatable\Versions;
use OpenSkill\Datatable\Queries\Datatable110QueryParser;
use OpenSkill\Datatable\Queries\QueryParser;
use OpenSkill\Datatable\Responses\Datatable110ResponseCreator;
use OpenSkill\Datatable\Responses\ResponseCreator;
use OpenSkill\Datatable\Views\Datatable110ViewCreator;
use OpenSkill\Datatable\Views\ViewCreator;

/**
 * Class Datatable110Version
 * @package OpenSkill\Datatable\Versions
 *
 * Version that supports the 1.10 version of datatables
 * http://datatables.net/
 *
 */
class Datatable110Version implements Version
{
    /** @var Datatable110QueryParser */
    private $queryParser;

    /** @var Datatable110ResponseCreator */
    private $responseCreator;

    /** @var  Datatable110ViewCreator */
    private $viewCreator;

    /**
     * Datatable110Version constructor.
     *
     * @param Datatable110QueryParser $queryParser a custom subclass for the query parser
     * @param Datatable110ResponseCreator $responseCreator a custom subclass for the response
     * @param Datatable110ViewCreator $viewCreator a custom subclass for the view
     */
    public function __construct(
        Datatable110QueryParser $queryParser = null,
        Datatable110ResponseCreator $responseCreator = null,
        Datatable110ViewCreator $viewCreator = null
    )
    {
        if(is_null($queryParser)) {
            $this->queryParser = new Datatable110QueryParser();
        } else {
            $this->queryParser = $queryParser;
        }

        if(is_null($responseCreator)) {
            $this->responseCreator = new Datatable110ResponseCreator();
        } else {
            $this->responseCreator= $responseCreator;
        }

        if(is_null($viewCreator)) {
            $this->viewCreator = new Datatable110ViewCreator();
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