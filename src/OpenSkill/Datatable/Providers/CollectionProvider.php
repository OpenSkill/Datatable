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
     * @var callable the default global function to check if a row should be included
     */
    private $defaultGlobalSearchFunction;

    /**
     * @var callable the default column search function to check if a row should be included
     */
    private $defaultColumnSearchFunction;

    /**
     * @var array an array of callables with local search functions to check if the row should be included
     */
    private $columnSearchFunction = [];

    /**
     * @var array an array that will hold the search functions for each column
     */
    private $columnConfiguration = [];

    /**
     * CollectionProvider constructor.
     * @param Collection $collection The collection with the initial data
     */
    public function __construct(Collection $collection)
    {
        $this->collection = $collection;
        // define search functions
        $this->globalSearchFunction = function ($data, $search) {
            foreach ($data as $item) {
                if (str_contains(mb_strtolower($item), mb_strtolower($search))) {
                    return true;
                }
            }
            return false;
        };
    }

    /**
     * Here the DTQueryConfiguration is passed to prepare the provider for the processing of the request.
     * This will only be called when the DTProvider needs to handle the request.
     * It will never be called when the DTProvider does not need to handle the request.
     *
     * @param QueryConfiguration $queryConfiguration
     * @param ColumnConfiguration[] $columnConfiguration
     * @return mixed
     */
    public function prepareForProcessing(QueryConfiguration $queryConfiguration, array $columnConfiguration)
    {
        $this->queryConfiguration = $queryConfiguration;
        $this->columnConfiguration = $columnConfiguration;

        // generate a custom search function for each column
        foreach ($this->columnConfiguration as $col) {
            if (!array_key_exists($col->getName(), $this->columnSearchFunction)) {
                $this->columnSearchFunction[$col->getName()] = function ($data, $search) use ($col) {
                    if (str_contains(mb_strtolower($data[$col->getName()]), mb_strtolower($search))) {
                        return true;
                    }
                    return false;
                };
            }
        }
    }

    /**
     * This method should process all configurations and prepare the underlying data for the view. It will arrange the
     * data and provide the results in a DTData object.
     * It will be called after {@link #prepareForProcessing} has been called and needs to return the processed data in
     * a DTData object so the Composer can further handle the data.
     *
     * @return Collection The processed data
     *
     */
    public function process()
    {
        // check if the query configuration is set
        if (is_null($this->queryConfiguration) || empty($this->columnConfiguration)) {
            throw new \InvalidArgumentException("Provider was not configured. Did you call prepareForProcessing first?");
        }

        // compile the collection first
        $this->compileCollection($this->columnConfiguration);

        // sort
        $this->sortCollection();

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
    private function compileCollection(array $columnConfiguration)
    {
        $searchFunc = null;
        if ($this->queryConfiguration->isGlobalSearch() && !is_null($this->globalSearchFunction)) {
            $searchFunc = $this->globalSearchFunction;
        }

        $this->collection->transform(function ($data) use ($columnConfiguration, $searchFunc) {
            $entry = [];
            // for each column call the callback
            foreach ($columnConfiguration as $i => $col) {
                $func = $col->getCallable();
                $entry[$col->getName()] = $func($data);

                if ($this->queryConfiguration->hasSearchColumn($col->getName())) {
                    // column search exists, so check if the column matches the search
                    if (!$this->columnSearchFunction[$col->getName()]($entry, $this->queryConfiguration->searchColumns()[$col->getName()])) {
                        // did not match, so return an empty array, the row will be removed later
                        return [];
                    }
                }
            }
            // also do search right away
            if (!is_null($searchFunc)) {
                if (!$searchFunc($entry, $this->queryConfiguration->searchValue())) {
                    $entry = [];
                }
            }
            return $entry;
        });

        $this->collection = $this->collection->reject(function ($data) {
            if (empty($data)) {
                return true;
            } else {
                return false;
            }
        });
    }

    /**
     * Will accept a search function that should be called for the column with the given name.
     * If the function returns true, it will be accepted as search matching
     *
     * @param string $columnName the name of the column to pass this function to
     * @param callable $searchFunction the function for the searching
     * @return $this
     */
    public function searchColumn($columnName, callable $searchFunction)
    {
        $this->columnSearchFunction[$columnName] = $searchFunction;
        return $this;
    }

    /**
     * Will accept a global search function for all columns.
     * @param callable $searchFunction the search function to determine if a row should be included
     * @return $this
     */
    public function search(callable $searchFunction)
    {
        $this->defaultGlobalSearchFunction = $searchFunction;
        return $this;
    }

    /**
     * Will sort the internal collection based on the given query configuration.
     * All tables only support the ordering by just one column, so if there is ordering just take the first ordering
     */
    private function sortCollection()
    {
        if ($this->queryConfiguration->hasOrderColumn()) {
            $order = $this->queryConfiguration->orderColumns()[0];
            $this->collection->sort(function ($first, $second) use ($order) {
                return strnatcmp($first[$order->columnName()], $second[$order->columnName()]);
            });
            if(!$order->isAscending()) {
                $this->collection->reverse();
            }
        }
    }
}