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
        $this->compileQuery($this->columnConfiguration);

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
    private function compileQuery(array $columnConfiguration)
    {
        if ($this->queryConfiguration->isGlobalSearch()) {
            $this->compileGlobalQuery($columnConfiguration);
        } elseif ($this->queryConfiguration->isColumnSearch()) {
            $this->compileColumnQuery($columnConfiguration);
        }
    }

    /**
     * When a global (single) search has been done against data in the datatable.
     *
     * @param array $columnConfiguration
     * @return QueryBuilder
     * @throws DatatableException
     */
    private function compileGlobalQuery(array $columnConfiguration)
    {
        foreach ($columnConfiguration as $i => $col) {
            $this->createQueryForColumn($col, $this->queryConfiguration->searchValue());
        }
    }

    /**
     * When a global query is being performed (ie, a query against a single column)
     *
     * @param ColumnConfiguration[] $columnConfiguration
     * @return QueryBuilder
     * @throws DatatableException
     */
    private function compileColumnQuery(array $columnConfiguration)
    {
        $searchColumns = $this->queryConfiguration->searchColumns();

        foreach ($searchColumns as $i => $col) {
            $column = $this->getColumnFromName($columnConfiguration, $col->columnName());

            if (!isset($column))
                continue;

            $this->createQueryForColumn($column, $col->searchValue());
        }
    }

    /**
     * Create the query w/ QueryBuilder
     * @param ColumnConfiguration $column
     * @param $searchValue
     * @return QueryBuilder
     * @throws DatatableException
     */
    private function createQueryForColumn(ColumnConfiguration $column, $searchValue)
    {
        $searchType = $column->getSearch();

        if ($searchType == DefaultSearchable::NONE()) {
            // Don't do anything, this is not a searchable field
            return $this->query;
        } elseif ($searchType == DefaultSearchable::NORMAL()) {
            $this->query->orWhere($column->getName(), 'LIKE', '%' . $searchValue . '%');
        } elseif ($searchType == DefaultSearchable::REGEX()) {
            $this->query->orWhere($column->getName(), 'REGEXP', $searchValue);
        } else {
            throw new DatatableException('An unsupported DefaultSearchable was provided.');
        }

        return $this->query;
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
}