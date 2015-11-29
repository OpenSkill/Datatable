<?php

namespace OpenSkill\Datatable\Columns\Searchable;

/**
 * Class Searchable
 * @package OpenSkill\Datatable\Columns
 *
 * Enum class that indicates the search option for the column
 */
abstract class Searchable {

    /**
     * Will construct a new searchable instance that is not searchable and is not regex searchable.
     * @return NoneSearchable
     */
    public static function NONE() {
        return new NoneSearchable();
    }

    /**
     * Will construct a new searchable that will allow normal searching but not regex searching.
     * @return DefaultSearchable
     */
    public static function NORMAL() {
        return new DefaultSearchable();
    }

    /**
     * Will return a new searchable that will allow normal searching which also allows regex searching.
     * @return RegexSearchable
     */
    public static function REGEX() {
        return new RegexSearchable();
    }

    /**
     * Will return if this configuration allows searching on the column.
     * @return bool
     */
    abstract public function isSearchable();

    /**
     * Will determine if the column content matches the given $searchValue.
     * @param string $searchValue The value to search for in this column.
     * @return bool Will return true if the content matches and false if it does not match.
     */
    abstract public function matches($searchValue);
}