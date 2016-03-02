<?php

namespace OpenSkill\Datatable\Versions;


use OpenSkill\Datatable\Columns\ColumnConfiguration;
use OpenSkill\Datatable\Data\ResponseData;
use OpenSkill\Datatable\Queries\Parser\Datatable19QueryParser;
use OpenSkill\Datatable\Queries\Parser\QueryParser;
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
class Datatable19Version extends DatatableVersion
{
    /**
     * Datatable19Version constructor.
     *
     * @param RequestStack $requestStack The current request
     */
    public function __construct(RequestStack $requestStack)
    {
        parent::__construct($requestStack, new Datatable19QueryParser());
    }

    /**
     * Is responsible to take the generated data and prepare a response for it.
     * @param ResponseData $data The processed data.
     * @param QueryConfiguration $queryConfiguration the query configuration for the current request.
     * @param ColumnConfiguration[] $columnConfigurations the column configurations for the current data table.
     * @return JsonResponse the response that should be returned to the client.
     */
    public function createResponse(
        ResponseData $data,
        QueryConfiguration $queryConfiguration,
        array $columnConfigurations
    ) {
        $responseData = [
            'sEcho' => $queryConfiguration->drawCall(),
            'iTotalRecords' => $data->totalDataCount(),
            'iTotalDisplayRecords' => $data->filteredDataCount(),
            'aaData' => $data->data()->toArray()
        ];

        return new JsonResponse($responseData);
    }

    /**
     * @return string The name of the view that this version should use fot the table.
     */
    public function tableView()
    {
        return "datatable::table";
    }

    /**
     * @return string The name of the view that this version should use for the script.
     */
    public function scriptView()
    {
        return "datatable::datatable19";
    }
}
