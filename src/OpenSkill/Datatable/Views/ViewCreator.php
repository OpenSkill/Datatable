<?php


namespace OpenSkill\Datatable\Views;

use OpenSkill\Datatable\Columns\ColumnConfiguration;

/**
 * Interface DTViewCreator
 * @package packages\openskill\datatable\src\OpenSkill\Datatable\View
 *
 * Base interface that is used to create a specific javascript declaration on the view
 */
interface ViewCreator
{
    public function createTable(ColumnConfiguration $columnConfiguration);
    public function createJavascript(ColumnConfiguration $columnConfiguration);
}