<?php

namespace OpenSkill\Datatable\Versions;


use OpenSkill\Datatable\Queries\DT19QueryParser;
use OpenSkill\Datatable\Queries\DTQueryParser;
use OpenSkill\Datatable\Response\DT19ResponseCreator;
use OpenSkill\Datatable\Response\DTResponseCreator;
use OpenSkill\Datatable\Views\DT19ViewCreator;
use OpenSkill\Datatable\Views\DTViewCreator;

/**
 * Class Datatable19Version
 * @package OpenSkill\Datatable\Versions
 *
 * Version that supports the 1.9 version of datatables
 * http://legacy.datatables.net/index
 *
 */
class Datatable19Version implements DTVersion
{
    /** @var DT19QueryParser  */
    private $queryParser;

    /** @var DT19ResponseCreator  */
    private $responseCreator;

    /** @var  DT19ViewCreator */
    private $viewCreator;

    /**
     * Datatable19Version constructor.
     *
     * @param DT19QueryParser $queryParser a custom subclass for the query parser
     * @param DT19ResponseCreator $responseCreator a custom subclass for the response
     * @param DT19ViewCreator $viewCreator a custom subclass for the view
     */
    public function __construct(
        DT19QueryParser $queryParser = null,
        DT19ResponseCreator $responseCreator = null,
        DT19ViewCreator $viewCreator = null
    )
    {
        if(is_null($queryParser)) {
            $this->queryParser = new DT19QueryParser();
        } else {
            $this->queryParser = $queryParser;
        }

        if(is_null($responseCreator)) {
            $this->responseCreator = new DT19ResponseCreator();
        } else {
            $this->responseCreator= $responseCreator;
        }

        if(is_null($viewCreator)) {
            $this->viewCreator = new DT19ViewCreator();
        } else {
            $this->viewCreator = $viewCreator;
        }
    }

    /**
     * Will get the DTQueryParser that is used to support this version of the data table
     *
     * @return DTQueryParser
     */
    public function queryParser()
    {
        return $this->queryParser;
    }

    /**
     * Will return the DTResponseCreator that is used to support this version of the data table
     *
     * @return DTResponseCreator
     */
    public function responseCreator()
    {
        return $this->responseCreator;
    }

    /**
     * Will return the DTViewCreator that is used to support this version of the data table
     *
     * @return DTViewCreator
     */
    public function viewCreator()
    {
        return $this->viewCreator;
    }
}