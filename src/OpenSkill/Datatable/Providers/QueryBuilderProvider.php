<?php

namespace OpenSkill\Datatable\Providers;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use OpenSkill\Datatable\Columns\ColumnConfiguration;
use OpenSkill\Datatable\Columns\ColumnOrder;
use OpenSkill\Datatable\Columns\ColumnSearch;
use OpenSkill\Datatable\Columns\Searchable\DefaultSearchable;
use OpenSkill\Datatable\Data\ResponseData;
use OpenSkill\Datatable\DatatableException;
use OpenSkill\Datatable\Queries\QueryConfiguration;

/**
 * Class CollectionProvider
 * @package OpenSkill\Datatable\Providers
 *
 * Provider that is able to provide data based on a initial passed collection.
 */
class QueryBuilderProvider implements Provider
{
    /**
     * @var QueryBuilder The original query, as passed to us.
     */
    private $originalQuery;

    /**
     * @var QueryBuilder The query, before limits are applied
     */
    private $queryBeforeLimits;

    /**
     * @var QueryBuilder The underlying query
     */
    private $query;

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
     * CollectionProvider constructor.
     * @param QueryBuilder $query The query to base the built query on
     */
    public function __construct(QueryBuilder $query)
    {
        $this->originalQuery = $query;
        $this->query = clone $query;

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

        // compile the query first
        $this->compileQuery($this->query, $this->columnConfiguration);

        // sort
        $this->sortQuery();

        // limit
        $this->queryBeforeLimits = clone $this->query;
        $this->limitQuery();

        // original # of items
        $filteredItems = $this->originalQuery->count();

        // # of items in filtered & ordered dataset
        $dataCount = $this->queryBeforeLimits->count();

        // the data for the response
        $columns = $this->compileColumnNames();
        $response = new Collection($this->query->get($columns));

        // slice the result into the right size
        return new ResponseData(
            $response,
            $filteredItems,
            $dataCount
        );
    }

    /**
     * Will compile the collection into the final collection where operations like search and order can be applied.
     *
     * @param QueryBuilder $query
     * @param ColumnConfiguration[] $columnConfiguration
     * @return QueryBuilder
     * @throws DatatableException
     */
    private function compileQuery(QueryBuilder $query, array $columnConfiguration)
    {
        if ($this->queryConfiguration->isGlobalSearch()) {
            $query = $this->compileGlobalQuery($query, $columnConfiguration);
        } elseif ($this->queryConfiguration->isColumnSearch()) {
            $query = $this->compileColumnQuery($query, $columnConfiguration);
        }

        return $query;
    }

    /**
     * When a global (single) search has been done against data in the datatable.
     *
     * @param QueryBuilder $query
     * @param array $columnConfiguration
     * @return QueryBuilder
     * @throws DatatableException
     */
    private function compileGlobalQuery(QueryBuilder $query, array $columnConfiguration)
    {
        foreach ($columnConfiguration as $i => $col) {
            if ($col->getSearch() == DefaultSearchable::NONE()) {
                // Don't do anything, this is not a searchable field
            } elseif ($col->getSearch() == DefaultSearchable::NORMAL()) {
                $query->orWhere($col->getName(), 'LIKE', '%' . $this->queryConfiguration->searchValue() . '%');
            } else {
                throw new DatatableException('An unsupported DefaultSearchable was provided.');
            }
        }

        return $query;
    }

    /**
     * When a global query is being performed (ie, a query against a single column)
     *
     * @param QueryBuilder $query
     * @param ColumnConfiguration[] $columnConfiguration
     * @return QueryBuilder
     * @throws DatatableException
     */
    private function compileColumnQuery(QueryBuilder $query, array $columnConfiguration)
    {
        $searchColumns = $this->queryConfiguration->searchColumns();

        foreach ($searchColumns as $i => $col) {
            $column = $this->getColumnFromName($columnConfiguration, $col->columnName());
            if (!isset($column))
                continue;

            if ($column->getSearch() == DefaultSearchable::NONE()) {
                // Don't do anything, this is not a searchable field
            } elseif ($column->getSearch() == DefaultSearchable::NORMAL()) {
                $query->orWhere($col->columnName(), 'LIKE', '%' . $col->searchValue() . '%');
            } else {
                throw new DatatableException('An unsupported DefaultSearchable was provided.');
            }
        }

        return $query;
    }

    /**
     * @param ColumnConfiguration[] $columnConfiguration
     * @param string $name
     * @return ColumnConfiguration
     */
    private function getColumnFromName($columnConfiguration, $name)
    {
        foreach ($columnConfiguration as $i => $col) {
            if ($col->getName() == $name)
                return $col;
        }
    }

    /**
     * When a global query is being performed (ie, a query against
     */
    private function compileColumnNames()
    {
        $columns = [];
        foreach($this->columnConfiguration as $column) {
            $columns[] = $column->getName();
        }

        return $columns;
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
        return $this;
    }

    /**
     * Will accept a global search function for all columns.
     * @param callable $orderFunction the order function to determine the order of the table
     * @return $this
     */
    public function order(callable $orderFunction)
    {
        return $this;
    }

    /**
     * Will sort the query based on the given datatable query configuration.
     */
    private function sortQuery()
    {
        if ($this->queryConfiguration->hasOrderColumn()) {
            $orderColumns = $this->queryConfiguration->orderColumns();

            foreach($orderColumns as $order) {
                $this->query->orderBy($order->columnName(), $order->isAscending() ? 'asc' : 'desc');
            }
        }
    }

    /**
     * Will limit a query hased on the start and length given
     */
    private function limitQuery()
    {
        $this->query->skip($this->queryConfiguration->start());
        $this->query->limit($this->queryConfiguration->length());
    }

    public function setupSearch()
    {
    }

    public function setupOrder()
    {
    }
}