<?php

namespace OpenSkill\Datatable\Providers;

use Illuminate\Support\Collection;
use OpenSkill\Datatable\Columns\ColumnConfiguration;
use OpenSkill\Datatable\Data\ResponseData;
use OpenSkill\Datatable\Queries\QueryConfiguration;

/**
 * Interface DatatableProvider
 * @package OpenSkill\Datatable\Providers
 *
 * Base interface for all datatable providers. A datatable provider will process the underlying data based on the
 *given configuration that it will get.
 */
interface Provider
{

    /**
     * Here the DTQueryConfiguration is passed to prepare the provider for the processing of the request.
     * This will only be called when the DTProvider needs to handle the request.
     * It will never be called when the DTProvider does not need to handle the request.
     *
     * @param QueryConfiguration $queryConfiguration
     * @param ColumnConfiguration[] $columnConfiguration
     */
    public function prepareForProcessing(QueryConfiguration $queryConfiguration, array $columnConfiguration);

    /**
     * This method should process all configurations and prepare the underlying data for the view. It will arrange the
     * data and provide the results in a DTData object.
     * It will be called after {@link #prepareForProcessing} has been called and needs to return the processed data in
     * a DTData object so the Composer can further handle the data.
     *
     * @return ResponseData The processed data
     */
    public function process();
}