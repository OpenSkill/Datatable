<?php

namespace OpenSkill\Datatable\Versions;


use OpenSkill\Datatable\Columns\ColumnConfiguration;
use OpenSkill\Datatable\Data\ResponseData;
use OpenSkill\Datatable\Queries\Parser\Datatable19QueryParser;
use OpenSkill\Datatable\Queries\QueryConfiguration;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class Datatable19Version
 * @package OpenSkill\Datatable\Versions
 *
 * Version that supports the 1.9 version of datatables
 * http://legacy.datatables.net/index
 *
 */
class Datatable19Version extends Version
{
    /** @var Datatable19QueryParser */
    private $queryParser;

    /**
     * Datatable19Version constructor.
     *
     * @param RequestStack $requestStack The current request
     */
    public function __construct(RequestStack $requestStack)
    {
        parent::__construct($requestStack);
        $this->queryParser = new Datatable19QueryParser();
    }

    /**
     * Method to determine if this parser can handle the query parameters. If so then the parser should return true
     * and be able to return a DTQueryConfiguration
     *
     * @return bool true if the parser is able to parse the query parameters and to return a DTQueryConfiguration
     */
    public function canParseRequest()
    {
        $request = $this->requestStack->getCurrentRequest();
        if (is_null($request)) {
            throw new \InvalidArgumentException("Can not determine a request that is null");
        }
        return $this->queryParser->canParse($request);
    }

    /**
     * Method that should parse the request and return a DTQueryConfiguration
     *
     * @param ColumnConfiguration[] $columnConfiguration The configuration of the columns
     *
     * @return QueryConfiguration the configuration the provider can use to prepare the data
     */
    public function parseRequest(array $columnConfiguration)
    {
        $request = $this->requestStack->getCurrentRequest();
        if (is_null($request)) {
            throw new \InvalidArgumentException("Can not parse a request that is null");
        }
        return $this->queryParser->parse($request, $columnConfiguration);
    }

    /**
     * Is responsible to take the generated data and prepare a response for it.
     * @param ResponseData $data The processed data.
     * @param QueryConfiguration $queryConfiguration the query configuration for the current request.
     * @param ColumnConfiguration[] $columnConfigurations the column configurations for the current data table.
     * @return JsonResponse the response that should be returned to the client.
     */
    public function createResponse(ResponseData $data, QueryConfiguration $queryConfiguration, array $columnConfigurations)
    {
        $responseData = [
            'sEcho' => $queryConfiguration->drawCall(),
            'iTotalRecords' => $data->totalDataCount(),
            'iTotalDisplayRecords' => $data->data()->count(),
            'aaData' => $data->data()->toArray()
        ];
        return new JsonResponse($responseData);
    }

    /**
     * @return string The name of the view that this version should use fot the table.
     */
    public function getTableView()
    {
        return "viewTableStuff";
    }

    /**
     * @return string The name of the view that this version should use for the script.
     */
    public function getScriptView()
    {
        return "scriptViewStuff";
    }
}