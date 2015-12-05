<?php

namespace OpenSkill\Datatable\Columns;

class ColumnSearch
{

    /** @var string */
    private $columnName;

    /** @var string */
    private $searchValue;

    /**
     * ColumnSearch constructor.
     * @param string $columnName
     * @param string $searchValue
     */
    public function __construct($columnName, $searchValue)
    {
        $this->columnName = $columnName;
        $this->searchValue = $searchValue;
    }

    /**
     * @return string
     */
    public function columnName()
    {
        return $this->columnName;
    }

    /**
     * @return string
     */
    public function searchValue()
    {
        return $this->searchValue;
    }
}