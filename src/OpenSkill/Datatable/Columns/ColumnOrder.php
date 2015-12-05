<?php

namespace OpenSkill\Datatable\Columns;

class ColumnOrder
{

    /** @var string */
    private $columnName;

    /** @var string */
    private $isAscOrdering;

    /**
     * ColumnOrder constructor.
     * @param string $columnName the internal name of the column
     * @param bool $isAscOrdering true if the ordering is ascending
     */
    public function __construct($columnName, $isAscOrdering)
    {
        $this->columnName = $columnName;
        $this->isAscOrdering = $isAscOrdering;
    }

    /**
     * @return string
     */
    public function columnName()
    {
        return $this->columnName;
    }

    /**
     * @return bool
     */
    public function isAscending()
    {
        return $this->isAscOrdering;
    }
}