<?php

namespace OpenSkill\Datatable\Columns\Orderable;

class AscOrderable extends Orderable
{

    /**
     * Will determine if the current configuration allows ordering.
     * @return bool True if the column can be ordered, false if not.
     */
    public function isOrderable()
    {
        return true;
    }
}