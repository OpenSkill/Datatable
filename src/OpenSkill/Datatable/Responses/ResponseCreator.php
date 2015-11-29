<?php

namespace OpenSkill\Datatable\Responses;

use Illuminate\Http\Response;
use OpenSkill\Datatable\Interfaces\Data;
use OpenSkill\Datatable\Queries\QueryConfiguration;

/**
 * Interface DTResponseCreator
 * @package OpenSkill\Datatable\Responses
 *
 * Base interface that is used to create custom responses for the data table request
 */
interface ResponseCreator
{
    /**
     * Is responsible to take the generated data and prepare a response for it.
     * @param Data $data The processed data.
     * @param QueryConfiguration $queryConfiguration the query configuration for the current request.
     * @param array $columnConfiguration the column configurations for the current data table.
     * @return Response the response that should be returned to the client.
     */
    public function createResponse(Data $data, QueryConfiguration $queryConfiguration, array $columnConfiguration);
}