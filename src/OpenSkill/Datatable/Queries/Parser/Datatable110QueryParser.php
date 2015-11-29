<?php

namespace OpenSkill\Datatable\Queries\Parser;

use Illuminate\Http\Request;
use OpenSkill\Datatable\Columns\ColumnConfiguration;
use OpenSkill\Datatable\Queries\QueryConfiguration;
use Symfony\Component\HttpFoundation\Response;

class Datatable110QueryParser extends QueryParser
{

    /**
     * Datatable110QueryParser constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    /**
     * Method to determine if this parser can handle the query parameters. If so then the parser should return true
     * and be able to return a DTQueryConfiguration
     *
     * @return bool true if the parser is able to parse the query parameters and to return a DTQueryConfiguration
     */
    public function canParse()
    {
        // check if draw is set and sEcho not
        return !$this->request->query->has("sEcho") && $this->request->query->has("draw");
    }

    /**
     * Method that should parse the request and return a DTQueryConfiguration
     *
     * @return QueryConfiguration the configuration the provider can use to prepare the data
     * @param ColumnConfiguration[] $columnConfiguration The configuration of the columns
     */
    public function parse(array $columnConfiguration)
    {
        // TODO: Implement parse() method.
    }
}