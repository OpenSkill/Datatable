<?php

namespace OpenSkill\Datatable\Queries;

class DTQueryConfigurationBuilder
{

    /** @var string */
    protected $drawCall = 1;

    /** @var int */
    protected $start = 1;

    /** @var int */
    protected $length = 10;

    /**
     * DTQueryConfigurationBuilder constructor.
     */
    private function __construct()
    {
    }

    public static function create()
    {
        return new DTQueryCOnfigurationBuilder();
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

    public function build()
    {
        return new DTQueryConfiguration(
            $this->drawCall,
            $this->start,
            $this->length
        );
    }
}