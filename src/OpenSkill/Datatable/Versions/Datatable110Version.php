<?php

namespace OpenSkill\Datatable\Versions;


use OpenSkill\Datatable\Columns\ColumnConfiguration;
use OpenSkill\Datatable\Data\ResponseData;
use OpenSkill\Datatable\Queries\Parser\Datatable110QueryParser;
use OpenSkill\Datatable\Queries\QueryConfiguration;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class Datatable110Version
 * @package OpenSkill\Datatable\Versions
 *
 * Version that supports the 1.10 version of datatables
 * http://datatables.net/index
 *
 */
class Datatable110Version extends DatatableVersion
{
    /**
     * Datatable110Version constructor.
     *
     * @param RequestStack $requestStack The current request
     */
    public function __construct(RequestStack $requestStack)
    {
        parent::__construct($requestStack, new Datatable110QueryParser());
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
            'draw' => $queryConfiguration->drawCall(),
            'recordsTotal' => $data->totalDataCount(),
            'recordsFiltered' => $data->filteredDataCount(),
            'data' => $data->data()->toArray()
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
        return "datatable::datatable110";
    }
}