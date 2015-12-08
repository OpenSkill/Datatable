<?php

namespace OpenSkill\Datatable\Data;

use Illuminate\Support\Collection;


/**
 * Class ResponseData
 * @package OpenSkill\Datatable\Data
 *
 * Will hold all information that are necessary to create a response.
 */
class ResponseData
{
    /** @var Collection */
    private $data;

    /** @var int */
    private $totalDataCount;

    /**
     * ResponseData constructor.
     * @param Collection $data the items that are returned from the provider
     * @param int $totalDataCount the count of the total items that the provider started with
     */
    public function __construct(Collection $data, $totalDataCount)
    {
        $this->data = $data;
        $this->totalDataCount = $totalDataCount;
    }

    /**
     * @return Collection
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * @return int
     */
    public function totalDataCount()
    {
        return $this->totalDataCount;
    }


}