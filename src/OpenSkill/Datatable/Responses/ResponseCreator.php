<?php

namespace OpenSkill\Datatable\Responses;

use OpenSkill\Datatable\Columns\ColumnConfiguration;
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
    public function createResponse(Data $data, QueryConfiguration $queryConfiguration, ColumnConfiguration $columnConfiguration);
}