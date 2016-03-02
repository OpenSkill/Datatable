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

    /** @var null|int */
    private $dataCount;

    /** @var int */
    private $totalDataCount;

    /**
     * ResponseData constructor.
     * @param Collection $data the items that are returned from the provider
     * @param int $totalDataCount the count of the total items that the provider started with
     * @param int $dataCount the count of the total items that have been sorted out in search
     */
    public function __construct(Collection $data, $totalDataCount, $dataCount = null)
    {
        $this->data = $data;
        $this->dataCount = $dataCount;
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

    /**
     * @return int
     */
    public function filteredDataCount()
    {
        if (is_null($this->dataCount)) {
            return count($this->data);
        }

        return $this->dataCount;
    }
}