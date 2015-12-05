<?php

namespace OpenSkill\Datatable\Responses;


use Illuminate\Http\Response;
use OpenSkill\Datatable\Columns\ColumnConfiguration;
use OpenSkill\Datatable\Data\ResponseData;
use OpenSkill\Datatable\Queries\QueryConfiguration;

class Datatable110ResponseCreator implements ResponseCreator
{

    /**
     * Is responsible to take the generated data and prepare a response for it.
     * @param ResponseData $data The processed data.
     * @param QueryConfiguration $queryConfiguration the query configuration for the current request.
     * @param ColumnConfiguration[] $columnConfigurations the column configurations for the current data table.
     * @return Response the response that should be returned to the client.
     */
    public function createResponse(ResponseData $data, QueryConfiguration $queryConfiguration, array $columnConfigurations)
    {
        // TODO: Implement createResponse() method.
    }
}