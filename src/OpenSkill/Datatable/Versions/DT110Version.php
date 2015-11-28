<?php

namespace OpenSkill\Datatable\Versions;
use OpenSkill\Datatable\Queries\DT110QueryParser;
use OpenSkill\Datatable\Queries\DTQueryParser;
use OpenSkill\Datatable\Responses\DT110ResponseCreator;
use OpenSkill\Datatable\Responses\DTResponseCreator;
use OpenSkill\Datatable\Views\DT110ViewCreator;
use OpenSkill\Datatable\Views\DTViewCreator;

/**
 * Class Datatable110Version
 * @package OpenSkill\Datatable\Versions
 *
 * Version that supports the 1.10 version of datatables
 * http://datatables.net/
 *
 */
class DT110Version implements DTVersion
{
    /** @var DT110QueryParser */
    private $queryParser;

    /** @var DT110ResponseCreator */
    private $responseCreator;

    /** @var  DT110ViewCreator */
    private $viewCreator;

    /**
     * Datatable110Version constructor.
     *
     * @param DT110QueryParser $queryParser a custom subclass for the query parser
     * @param DT110ResponseCreator $responseCreator a custom subclass for the response
     * @param DT110ViewCreator $viewCreator a custom subclass for the view
     */
    public function __construct(
        DT110QueryParser $queryParser = null,
        DT110ResponseCreator $responseCreator = null,
        DT110ViewCreator $viewCreator = null
    )
    {
        if(is_null($queryParser)) {
            $this->queryParser = new DT110QueryParser();
        } else {
            $this->queryParser = $queryParser;
        }

        if(is_null($responseCreator)) {
            $this->responseCreator = new DT110ResponseCreator();
        } else {
            $this->responseCreator= $responseCreator;
        }

        if(is_null($viewCreator)) {
            $this->viewCreator = new DT110ViewCreator();
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