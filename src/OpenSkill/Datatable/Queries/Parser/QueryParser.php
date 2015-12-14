<?php

namespace OpenSkill\Datatable\Queries\Parser;


use OpenSkill\Datatable\Columns\ColumnConfiguration;
use OpenSkill\Datatable\Queries\QueryConfiguration;
use Symfony\Component\HttpFoundation\Request;

abstract class QueryParser
{

    /**
     * Method to determine if this parser can handle the query parameters. If so then the parser should return true
     * and be able to return a DTQueryConfiguration
     *
     * @param Request $request The current request, that should be investigated
     * @return bool true if the parser is able to parse the query parameters and to return a DTQueryConfiguration
     */
    abstract public function canParse(Request $request);

    /**
     * Method that should parse the request and return a DTQueryConfiguration
     *
     * @param Request $request The current request that should be investigated
     * @param ColumnConfiguration[] $columnConfiguration The configuration of the columns
     * @return QueryConfiguration the configuration the provider can use to prepare the data
     */
    abstract public function parse(Request $request, array $columnConfiguration);

}