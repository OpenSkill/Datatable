<?php

namespace OpenSkill\Datatable\Responses;


use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use OpenSkill\Datatable\Data\ResponseData;
use OpenSkill\Datatable\Queries\QueryConfiguration;

class Datatable19ResponseCreator implements ResponseCreator
{
    /**
     * Is responsible to take the generated data and prepare a response for it.
     * @param ResponseData $data The processed data.
     * @param QueryConfiguration $queryConfiguration the query configuration for the current request.
     * @param array $columnConfiguration the column configurations for the current data table.
     * @return JsonResponse the response that should be returned to the client.
     */
    public function createResponse(ResponseData $data, QueryConfiguration $queryConfiguration, array $columnConfiguration)
    {
        // TODO: Implement createResponse() method.
        // will generate the response according to: http://legacy.datatables.net/usage/server-side
        $responseData = [
            'sEcho'                 => $queryConfiguration->drawCall(),
            'iTotalRecords'         => $data->totalDataCount(),
            'iTotalDisplayRecords'  => $data->data()->count(),
            'aaData'                => $data->data()->toArray()
        ];
        return new JsonResponse($responseData);
    }
}