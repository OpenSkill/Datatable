<?php

namespace OpenSkill\Datatable\Versions;
use OpenSkill\Datatable\Query\DTQueryParser;
use OpenSkill\Datatable\Response\DTResponseCreator;
use packages\openskill\datatable\src\OpenSkill\Datatable\View\DTViewCreator;

/**
 * Interface DTVersion
 * @package OpenSkill\Datatable\Versions
 *
 * Base interface that is used to support different frontend views from this data table plugin
 */
interface DTVersion
{
    /**
     * Will get the DTQueryParser that is used to support this version of the data table
     *
     * @return DTQueryParser
     */
    public function queryParser();

    /**
     * Will return the DTResponseCreator that is used to support this version of the data table
     *
     * @return DTResponseCreator
     */
    public function responseCreator();

    /**
     * Will return the DTViewCreator that is used to support this version of the data table
     *
     * @return DTViewCreator
     */
    public function viewCreator();
}