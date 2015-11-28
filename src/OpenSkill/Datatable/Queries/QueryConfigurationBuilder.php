<?php

namespace OpenSkill\Datatable\Queries;

class QueryConfigurationBuilder
{

    /** @var string */
    protected $drawCall = 1;

    /** @var int */
    protected $start = 1;

    /** @var int */
    protected $length = 10;

    /** @var string */
    protected $searchValue = null;

    /** @var bool */
    protected $searchRegex = false;

    /**
     * DTQueryConfigurationBuilder constructor.
     */
    private function __construct()
    {
    }

    public static function create()
    {
        return new QueryCOnfigurationBuilder();
    }

    public function drawCall($drawCall) {
        if(!is_string($drawCall) && !is_numeric($drawCall)) {
            throw new \InvalidArgumentException('$drawCall needs to be a string or numeric');
        }
        $this->drawCall = $drawCall;
    }

    public function start($start)
    {
        if(!is_numeric($start)) {
            throw new \InvalidArgumentException('$start needs to be numeric');
        }
        $this->start = $start;
    }

    public function length($length)
    {
        if(!is_numeric($length)) {
            throw new \InvalidArgumentException('$length needs to be numeric');
        }
        $this->length = $length;
    }

    public function searchValue($searchValue)
    {
        if(!is_string($searchValue)) {
            throw new \InvalidArgumentException('$searchValue needs to be a string');
        }
        $this->searchValue = $searchValue;
    }

    public function searchRegex($searchRegex)
    {
        if(!is_bool($searchRegex)) {
            throw new \InvalidArgumentException('$searchRegex needs to be a boolean');
        }
        $this->searchRegex = $searchRegex;
    }

    public function build()
    {
        return new QueryConfiguration(
            $this->drawCall,
            $this->start,
            $this->length,
            $this->searchValue,
            $this->searchRegex
        );
    }
}