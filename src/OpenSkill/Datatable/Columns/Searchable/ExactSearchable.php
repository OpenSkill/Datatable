<?php

namespace OpenSkill\Datatable\Columns\Searchable;

class ExactSearchable extends Searchable
{

    /**
     * Will return if this configuration allows searching on the column.
     * @return bool
     */
    public function isSearchable()
    {
        return true;
    }
}