<?php

namespace OpenSKill\Datatable\Providers;

use Illuminate\Support\Collection;
use OpenSKill\Datatable\Columns\ColumnConfiguration;
use OpenSKill\Datatable\Interfaces\DTData;
use OpenSKill\Datatable\Interfaces\DTQueryConfiguration;

/**
 * Class CollectionProvider
 * @package OpenSKill\Datatable\Providers
 *
 * Provider that is able to provide data based on a initial passed collection.
 */
class CollectionProvider implements DTProvider
{
    /**
     * @var Collection The underlying data
     */
    private $collection;

    /**
     * @var DTQueryConfiguration
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
     * @param DTQueryConfiguration $queryConfiguration
     * @return mixed
     */
    public function prepareForProcessing(DTQueryConfiguration $queryConfiguration)
    {
        $this->queryConfiguration = $queryConfiguration;
    }

    /**
     * This method should process all configurations and prepare the underlying data for the view. It will arrange the
     * data and provide the results in a DTData object.
     * It will be called after {@link #prepareForProcessing} has been called and needs to return the processed data in
     * a DTData object so the Composer can further handle the data.
     *
     * @param ColumnConfiguration[] $columnConfiguration
     * @return DTData The processed data
     *
     */
    public function process(array $columnConfiguration)
    {
        // prepare the data here
    }
}