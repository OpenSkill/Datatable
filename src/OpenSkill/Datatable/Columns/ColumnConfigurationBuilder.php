<?php

namespace OpenSkill\Datatable\Columns;
use OpenSkill\Datatable\Columns\Orderable\Orderable;
use OpenSkill\Datatable\Columns\Searchable\Searchable;

/**
 * Class ColumnConfigurationBuilder
 * @package OpenSkill\Datatable\Columns
 *
 * Simple builder class for the column configuration
 */
class ColumnConfigurationBuilder
{

    /**
     * @var string
     */
    private $name = null;

    /**
     * @var callable
     */
    private $callable = null;

    /**
     * @var Searchable
     */
    private $searchable = null;

    /**
     * @var Orderable
     */
    private $orderable = null;

    /**
     * ColumnConfigurationBuilder constructor.
     */
    private function __construct()
    {
    }

    /**
     * Will create a new builder for a ColumnConfigurationBuilder.
     *
     * @return ColumnConfigurationBuilder
     */
    public static function create()
    {
        return new ColumnConfigurationBuilder();
    }

    /**
     * @param string $name
     * @return $this
     */
    public function name($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param Searchable $searchable
     * @return $this
     */
    public function searchable(Searchable $searchable)
    {
        $this->searchable = $searchable;
        return $this;
    }

    /**
     * @param Orderable $orderable
     * @return $this
     */
    public function orderable(Orderable $orderable)
    {
        $this->orderable = $orderable;
        return $this;
    }

    /**
     * @param callable $callable
     * @return $this
     */
    public function withCallable($callable)
    {
        $this->callable = $callable;
        return $this;
    }

    /**
     * Will create the final ColumnConfiguration
     *
     * @return ColumnConfiguration
     */
    public function build()
    {
        $this->checkName();
        $this->checkCallable();
        $this->checkOrderable();
        $this->checkSearchable();

        return new ColumnConfiguration($this->name, $this->callable, $this->searchable, $this->orderable);
    }

    /**
     * Will check if the name is empty and throws an exception if that is the case.
     */
    private function checkName()
    {
        if(empty($this->name))
        {
            throw new \InvalidArgumentException("The name can not be empty");
        }
    }

    /**
     * Will check if the orderable flag is correctly set, otherwise it will be set to the default NONE
     */
    private function checkOrderable()
    {
        if($this->orderable == null) {
            $this->orderable = Orderable::NONE();
        }
    }

    /**
     * Will check if the searchable flag is correctly set, if not it will be set to the default NONE
     */
    private function checkSearchable()
    {
        if($this->searchable == null) {
            $this->searchable = Searchable::NONE();
        }
    }

    /**
     * Will check if the callable is set and is executable, if not a sensible default will be set.
     */
    private function checkCallable()
    {
        if(is_null($this->callable) || !is_callable($this->callable))
        {
            $self = $this;
            $this->callable = function($data) use (&$self) {
                $name = $self->name;

                if(is_array($data) && array_key_exists($name, $data))
                {
                    return $data[$name];
                } else if(is_object($data) && property_exists($data, $name))
                {
                    return $data->$name;
                } else if(is_object($data) && method_exists($data, $name))
                {
                    return $data->$name();
                } else {
                    return "";
                }
            };
        }
    }

}