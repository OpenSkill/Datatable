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
     * DTQueryConfigurationBuilder constructor, private by default, so new instances are created using the builder
     * pattern
     */
    private function __construct()
    {
    }

    /**
     * Will create a new QueryConfigurationBuilder internally and acts as static builder method
     * @return QueryConfigurationBuilder
     */
    public static function create()
    {
        return new QueryCOnfigurationBuilder();
    }

    /**
     * Will set the drawCall parameter send by the frontend.
     * @param string|int $drawCall The draw call parameter
     */
    public function drawCall($drawCall) {
        if(!is_string($drawCall) && !is_numeric($drawCall)) {
            throw new \InvalidArgumentException('$drawCall needs to be a string or numeric');
        }
        $this->drawCall = $drawCall;
    }

    /**
     * Will set the start parameter which indicates how many items should be skipped at the start
     * @param int $start
     */
    public function start($start)
    {
        if(!is_numeric($start)) {
            throw new \InvalidArgumentException('$start needs to be numeric');
        }
        $this->start = $start;
    }

    /**
     * Will set the length parameter which indicates how many items should be returned by this request.
     * @param int $length
     */
    public function length($length)
    {
        if(!is_numeric($length)) {
            throw new \InvalidArgumentException('$length needs to be numeric');
        }
        $this->length = $length;
    }

    /**
     * Will set the search value the frontend send that should be used for the global search
     * @param string $searchValue
     */
    public function searchValue($searchValue)
    {
        if(!is_string($searchValue)) {
            throw new \InvalidArgumentException('$searchValue needs to be a string');
        }
        $this->searchValue = $searchValue;
    }

    /**
     * Will indicate if the global search value should be used as a regular expression
     * @param bool $searchRegex
     */
    public function searchRegex($searchRegex)
    {
        if(!is_bool($searchRegex)) {
            throw new \InvalidArgumentException('$searchRegex needs to be a boolean');
        }
        $this->searchRegex = $searchRegex;
    }

    /**
     * Will build the final QueryConfiguration that will be used later in the process pipeline
     * @return QueryConfiguration
     */
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