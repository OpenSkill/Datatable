<?php

namespace OpenSkill\Datatable\Providers;

use Illuminate\Support\Collection;
use OpenSkill\Datatable\Columns\ColumnConfiguration;
use OpenSkill\Datatable\Columns\ColumnOrder;
use OpenSkill\Datatable\Columns\ColumnSearch;
use OpenSkill\Datatable\Data\ResponseData;
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
     * @var callable the default global order function
     */
    private $defaultGlobalOrderFunction;

    /**
     * @var array an array of callables with local search functions to check if the row should be included
     */
    private $columnSearchFunction = [];

    /**
     * @var array an array that will hold the search functions for each column
     */
    private $columnConfiguration = [];

    /**
     * @var int the initial count of the items before processing
     */
    private $totalInitialDataCount;

    /**
     * CollectionProvider constructor.
     * @param Collection $collection The collection with the initial data
     */
    public function __construct(Collection $collection)
    {
        $this->collection = $collection;
        $this->totalInitialDataCount = $collection->count();

        $this->setupSearch();
        $this->setupOrder();
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
                $this->columnSearchFunction[$col->getName()] = function ($data, ColumnSearch $search) use ($col) {
                    if (str_contains(mb_strtolower($data[$col->getName()]), mb_strtolower($search->searchValue()))) {
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
     * @return ResponseData The processed data
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
        return new ResponseData(
            $this->collection->slice(
                $this->queryConfiguration->start(),
                $this->queryConfiguration->length()
            ),
            $this->totalInitialDataCount

        );
    }


    /**
     * Will compile the collection into the final collection where operations like search and order can be applied.
     * @param ColumnConfiguration[] $columnConfiguration
     */
    private function compileCollection(array $columnConfiguration)
    {
        $searchFunc = null;
        if ($this->queryConfiguration->isGlobalSearch()) {
            $searchFunc = $this->defaultGlobalSearchFunction;
        }

        $this->transformCollectionData($columnConfiguration, $searchFunc);
        $this->removeEmptyRowsFromCollection();
    }

    /**
     * Transform collection data. Used for searches.
     *
     * @param ColumnConfiguration[] $columnConfiguration
     * @param $searchFunc
     */
    private function transformCollectionData($columnConfiguration, $searchFunc)
    {
        $this->collection->transform(function ($data) use ($columnConfiguration, $searchFunc) {
            $entry = [];
            // for each column call the callback
            foreach ($columnConfiguration as $i => $col) {
                $func = $col->getCallable();
                $entry[$col->getName()] = $func($data);

                if ($this->queryConfiguration->hasSearchColumn($col->getName())) {
                    // column search exists, so check if the column matches the search
                    if (!$this->columnSearchFunction[$col->getName()]($entry,
                        $this->queryConfiguration->searchColumns()[$col->getName()])
                    ) {
                        // did not match, so return an empty array, the row will be removed later
                        return [];
                    }
                }
            }

            // also do search right away
            if ($this->queryConfiguration->isGlobalSearch()) {
                if (!$searchFunc($entry, $this->queryConfiguration->searchValue(), $this->columnConfiguration)) {
                    $entry = [];
                }
            }
            return $entry;
        });
    }

    /**
     * Remove the empty rows from the collection
     *
     * @see compileCollection
     */
    private function removeEmptyRowsFromCollection()
    {
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
     * Will accept a global search function for all columns.
     * @param callable $orderFunction the order function to determine the order of the table
     * @return $this
     */
    public function order(callable $orderFunction)
    {
        $this->defaultGlobalOrderFunction = $orderFunction;
        return $this;
    }

    /**
     * Will sort the internal collection based on the given query configuration.
     * Most tables only support the ordering by just one column, but we will enable sorting on all columns here
     */
    private function sortCollection()
    {
        if ($this->queryConfiguration->hasOrderColumn()) {
            $order = $this->queryConfiguration->orderColumns();
            $orderFunc = $this->defaultGlobalOrderFunction;
            $this->collection = $this->collection->sort(function ($first, $second) use ($order, $orderFunc) {
                return $orderFunc($first, $second, $order);
            });
        }
    }

    public function setupSearch()
    {
        /**
         * @param array $data the generated data for this row
         * @param string $search the search value to look for
         * @param ColumnConfiguration[] $columns the configuration of the columns
         * @return bool true if the row should be included in the result, false otherwise
         */
        $this->defaultGlobalSearchFunction = function ($data, $search, array $columns) {
            foreach ($columns as $column) {
                if ($column->getSearch()->isSearchable() && str_contains(mb_strtolower($data[$column->getName()]),
                        mb_strtolower($search))
                ) {
                    return true;
                }
            }
            return false;
        };
    }

    public function setupOrder()
    {
        /**
         * @param array $first
         * @param array $second
         * @param ColumnOrder[] $orderColumn
         * @return int
         */
        $this->defaultGlobalOrderFunction = function (array $first, array $second, array $orderColumn) {
            foreach($orderColumn as $order) {
                if(array_key_exists($order->columnName(), $first)) {
                    $value = strnatcmp($first[$order->columnName()], $second[$order->columnName()]);
                    if($value == 0) {
                       continue;
                    }
                    if (!$order->isAscending()) {
                        return $value * -1;
                    }
                    return $value;
                }
            }
            return 0;
        };
    }
}