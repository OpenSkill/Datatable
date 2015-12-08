<?php

namespace OpenSkill\Datatable\Columns\Orderable;

/**
 * Class Orderable
 * @package OpenSkill\Datatable\Columns
 *
 * Class that indicates the option for the orderable property of a column
 */
abstract class Orderable
{
    /**
     * Will return an orderable configuration that does not allow ordering.
     * @return NoneOrderable
     */
    public static function NONE()
    {
        return new NoneOrderable();
    }

    /**
     * Will return an orderable configuration that does allow ordering but only on asc.
     * @return AscOrderable
     */
    public static function ASC()
    {
        return new AscOrderable();
    }

    /**
     * Will return an orderable configuration that does allow ordering but only on desc.
     * @return DescOrderable
     */
    public static function DESC()
    {
        return new DescOrderable();
    }

    /**
     * Will return an orderable configuration that does allow ordering on both directions.
     * @return BothOrderable
     */
    public static function BOTH()
    {
        return new BothOrderable();
    }

    /**
     * Will determine if the current configuration allows ordering.
     * @return bool True if the column can be ordered, false if not.
     */
    abstract public function isOrderable();
}