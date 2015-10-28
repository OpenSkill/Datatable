<?php

namespace OpenSKill\Datatable\Providers;

use OpenSKill\Datatable\Columns\ColumnConfiguration;
use OpenSKill\Datatable\Interfaces\DTData;
use OpenSKill\Datatable\Interfaces\DTQueryConfiguration;

/**
 * Interface DatatableProvider
 * @package OpenSkill\Datatable\Providers
 *
 * Base interface for all datatable providers. A datatable provider will process the underlying data based on the
 *given configuration that it will get.
 */
interface DTProvider
{

    /**
     * Here the DTQueryConfiguration is passed to prepare the provider for the processing of the request.
     * This will only be called when the DTProvider needs to handle the request.
     * It will never be called when the DTProvider does not need to handle the request.
     *
     * @param DTQueryConfiguration $queryConfiguration
     * @return mixed
     */
    public function prepareForProcessing(DTQueryConfiguration $queryConfiguration);

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
    public function process(array $columnConfiguration);
}