<?php

namespace OpenSkill\Datatable\Columns;

class ColumnOrder {

    /** @var string */
    private $columnName;

    /** @var string */
    private $orderValue;

    /**
     * ColumnOrder constructor.
     * @param string $columnName
     * @param string $orderValue
     */
    public function __construct($columnName, $orderValue)
    {
        $this->columnName = $columnName;
        $this->orderValue = $orderValue;
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
    public function orderValue()
    {
        return $this->orderValue;
    }

}