<?php

namespace OpenSkill\Datatable\Queries\Parser;

use Illuminate\Http\Request;
use OpenSkill\Datatable\Columns\ColumnConfiguration;
use OpenSkill\Datatable\Queries\QueryConfiguration;

/**
 * Interface DTQueryParser
 * @package OpenSkill\Datatable\Queries
 *
 * Base interface that all parsers needs to implement.
 */
abstract class QueryParser
{

    /** @var Request */
    protected $request;

    /**
     * QueryParser constructor.
     * @param Request $request The current request
     */
    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * Method to determine if this parser can handle the query parameters. If so then the parser should return true
     * and be able to return a DTQueryConfiguration
     *
     * @return bool true if the parser is able to parse the query parameters and to return a DTQueryConfiguration
     */
    public abstract function canParse();

    /**
     * Method that should parse the request and return a DTQueryConfiguration
     *
     * @param ColumnConfiguration[] $columnConfiguration The configuration of the columns
     *
     * @return QueryConfiguration the configuration the provider can use to prepare the data
     */
    public abstract function parse(array $columnConfiguration);
}