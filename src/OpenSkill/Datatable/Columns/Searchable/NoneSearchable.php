<?php

namespace OpenSkill\Datatable\Columns\Searchable;

class NoneSearchable extends Searchable
{

    /**
     * Will return if this configuration allows searching on the column.
     * @return bool
     */
    public function isSearchable()
    {
        return false;
    }

    /**
     * Will determine if the column content matches the given $searchValue.
     * @param string $searchValue The value to search for in this column.
     * @return bool Will return true if the content matches and false if it does not match.
     */
    public function matches($searchValue)
    {
        return false;
    }
}