<?php

namespace OpenSkill\Datatable\Versions;
use OpenSkill\Datatable\Queries\QueryParser;
use OpenSkill\Datatable\Responses\ResponseCreator;
use OpenSkill\Datatable\Views\ViewCreator;

/**
 * Interface DTVersion
 * @package OpenSkill\Datatable\Versions
 *
 * Base interface that is used to support different frontend views from this data table plugin
 */
interface Version
{
    /**
     * Will get the QueryParser that is used to support this version of the data table
     *
     * @return QueryParser
     */
    public function queryParser();

    /**
     * Will return the ResponseCreator that is used to support this version of the data table
     *
     * @return ResponseCreator
     */
    public function responseCreator();

    /**
     * Will return the ViewCreator that is used to support this version of the data table
     *
     * @return ViewCreator
     */
    public function viewCreator();
}