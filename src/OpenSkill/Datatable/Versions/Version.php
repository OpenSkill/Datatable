<?php

namespace OpenSkill\Datatable\Versions;

use OpenSkill\Datatable\Columns\ColumnConfiguration;
use OpenSkill\Datatable\Data\ResponseData;
use OpenSkill\Datatable\Queries\QueryConfiguration;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class Version
 * @package OpenSkill\Datatable\Versions
 *
 * Base interface that is used to support different frontend views from this data table plugin
 */
abstract class Version
{
    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * Version constructor.
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * Method to determine if this parser can handle the query parameters. If so then the parser should return true
     * and be able to return a DTQueryConfiguration
     *
     * @return bool true if the parser is able to parse the query parameters and to return a DTQueryConfiguration
     */
    abstract public function canParseRequest();

    /**
     * Method that should parse the request and return a DTQueryConfiguration
     *
     * @param ColumnConfiguration[] $columnConfiguration The configuration of the columns
     * @return QueryConfiguration the configuration the provider can use to prepare the data
     */
    abstract public function parseRequest(array $columnConfiguration);


    /**
     * Is responsible to take the generated data and prepare a response for it.
     * @param ResponseData $data The processed data.
     * @param QueryConfiguration $queryConfiguration the query configuration for the current request.
     * @param ColumnConfiguration[] $columnConfigurations the column configurations for the current data table.
     * @return JsonResponse the response that should be returned to the client.
     */
    abstract public function createResponse(ResponseData $data, QueryConfiguration $queryConfiguration, array $columnConfigurations);

    /**
     * @return string The name of the view that this version should use fot the table.
     */
    abstract public function getTableView();

    /**
     * @return string The name of the view that this version should use for the script.
     */
    abstract public function getScriptView();
}