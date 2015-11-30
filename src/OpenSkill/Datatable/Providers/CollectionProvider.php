<?php

namespace OpenSkill\Datatable\Providers;

use Illuminate\Support\Collection;
use OpenSkill\Datatable\Columns\ColumnConfiguration;
use OpenSkill\Datatable\Queries\QueryConfiguration;

/**
 * Class CollectionProvider
 * @package OpenSkill\Datatable\Providers
 *
 * Provider that is able to provide data based on a initial passed collection.
 */
class CollectionProvider implements Provider
{
    /**
     * @var Collection The underlying data
     */
    private $collection;

    /**
     * @var QueryConfiguration
     */
    private $queryConfiguration;

    /**
     * CollectionProvider constructor.
     * @param Collection $collection The collection with the initial data
     */
    public function __construct(Collection $collection)
    {
        $this->collection = $collection;
    }

    /**
     * Here the DTQueryConfiguration is passed to prepare the provider for the processing of the request.
     * This will only be called when the DTProvider needs to handle the request.
     * It will never be called when the DTProvider does not need to handle the request.
     *
     * @param QueryConfiguration $queryConfiguration
     * @return mixed
     */
    public function prepareForProcessing(QueryConfiguration $queryConfiguration)
    {
        $this->queryConfiguration = $queryConfiguration;
        // do stuff that is needed before the actual processing should be done
    }

    /**
     * This method should process all configurations and prepare the underlying data for the view. It will arrange the
     * data and provide the results in a DTData object.
     * It will be called after {@link #prepareForProcessing} has been called and needs to return the processed data in
     * a DTData object so the Composer can further handle the data.
     *
     * @param ColumnConfiguration[] $columnConfiguration
     * @return Collection The processed data
     *
     */
    public function process(array $columnConfiguration)
    {
        // check if the query configuration is set
        if(is_null($this->queryConfiguration)) {
            throw new \InvalidArgumentException("No query configuration found. Did you call prepareForProcessing first?");
        }

        // compile the collection first
        $this->compileCollection($columnConfiguration);

        // search
        // sort

        // slice the result into the right size
        return $this->collection->slice(
            $this->queryConfiguration->start(),
            $this->queryConfiguration->length()
        );
    }


    /**
     * Will compile the collection into the final collection where operations like search and order can be applied.
     * @param ColumnConfiguration[] $columnConfiguration
     */
    private function compileCollection(array $columnConfiguration) {
        $this->collection->transform(function($data) use ($columnConfiguration){
            $entry = [];
            // for each column call the callback
            foreach ($columnConfiguration as $i => $col)
            {
                $func = $col->getCallable();
                $entry[$col->getName()] =  $func($data);
            }
            return $entry;
        });
    }
}