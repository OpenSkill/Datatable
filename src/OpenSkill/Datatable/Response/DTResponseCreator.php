<?php

namespace OpenSkill\Datatable\Response;

use OpenSkill\Datatable\Columns\ColumnConfiguration;
use OpenSkill\Datatable\Interfaces\DTData;
use OpenSkill\Datatable\Interfaces\DTQueryConfiguration;

/**
 * Interface DTResponseCreator
 * @package OpenSkill\Datatable\Response
 *
 * Base interface that is used to create custom responses for the data table request
 */
interface DTResponseCreator
{
    public function createResponse(DTData $data, DTQueryConfiguration $queryConfiguration, ColumnConfiguration $columnConfiguration);
}