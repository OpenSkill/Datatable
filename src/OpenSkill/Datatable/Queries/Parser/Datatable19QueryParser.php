<?php

namespace OpenSkill\Datatable\Queries\Parser;


use Illuminate\Http\Request;
use OpenSkill\Datatable\Columns\ColumnConfiguration;
use OpenSkill\Datatable\Queries\QueryConfiguration;
use OpenSkill\Datatable\Queries\QueryConfigurationBuilder;
use Symfony\Component\HttpFoundation\Response;

class Datatable19QueryParser extends QueryParser
{

    /**
     * Datatable19QueryParser constructor.
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
        // check if sEcho is set and draw not
        return $this->request->query->has("sEcho") && !$this->request->query->has("draw");
    }

    /**
     * Method that should parse the request and return a DTQueryConfiguration
     *
     * @param ColumnConfiguration[] $columnConfiguration The configuration of the columns
     *
     * @return QueryConfiguration the configuration the provider can use to prepare the data
     */
    public function parse(array $columnConfiguration)
    {
        $query = $this->request->query;
        $builder = QueryConfigurationBuilder::create();

        if($query->has('sEcho')) {
            $builder->drawCall($query->get('sEcho'));
        }

        if($query->has('iDisplayStart')) {
            $builder->start($query->get('iDisplayStart'));
        }

        if($query->has('iDisplayLength')) {
            $builder->length($query->get('iDisplayLength'));
        }

        if($query->has('sSearch')) {
            $builder->searchValue($query->get('sSearch'));
        }

        if($query->has('bRegex')) {
            $builder->searchRegex($query->get('bRegex'));
        }

        return $builder->build();

    }
}