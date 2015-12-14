<?php

namespace OpenSkill\Datatable\Versions;


use OpenSkill\Datatable\Columns\ColumnConfiguration;
use OpenSkill\Datatable\Data\ResponseData;
use OpenSkill\Datatable\Queries\Parser\QueryParser;
use OpenSkill\Datatable\Queries\QueryConfiguration;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;

abstract class DatatableVersion extends Version
{
    /**
     * @var QueryParser
     */
    protected $queryParser;

    /**
     * DatatableVersion constructor.
     * @param RequestStack $requestStack
     * @param QueryParser $queryParser
     */
    public function __construct(RequestStack $requestStack, QueryParser $queryParser)
    {
        parent::__construct($requestStack);
        $this->queryParser = $queryParser;
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
}