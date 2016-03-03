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

        // compile the query first
        $this->compileQuery();

        // sort
        $this->sortQuery();
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

        // limit
        $this->queryBeforeLimits = clone $this->query;
        $this->limitQuery();

        // # of items in filtered & ordered data set
        $dataCount = $this->queryBeforeLimits->count();

        // the data for the response
        $columns = $this->compileColumnNames();
        $response = new Collection($this->query->get($columns));

        // slice the result into the right size
        return new ResponseData(
            $response,
            $this->getTotalNumberOfRows(),
            $dataCount
        );
    }

    /**
     * Get the total number of rows for the original query.
     * @return int
     */
    private function getTotalNumberOfRows()
    {
        return $this->originalQuery->count();
    }

    /**
     * Will compile the collection into the final collection where operations like search and order can be applied.
     *
     * @return QueryBuilder
     * @throws DatatableException
     */
    private function compileQuery()
    {
        if ($this->queryConfiguration->isGlobalSearch()) {
            $this->compileGlobalQuery();
        } elseif ($this->queryConfiguration->isColumnSearch()) {
            $this->compileColumnQuery();
        }
    }

    /**
     * When a global (single) search has been done against data in the datatable.
     *
     * @return QueryBuilder
     * @throws DatatableException
     */
    private function compileGlobalQuery()
    {
        foreach ($this->columnConfiguration as $i => $col) {
            $this->createQueryForColumn($col, $this->queryConfiguration->searchValue());
        }
    }

    /**
     * When a global query is being performed (ie, a query against a single column)
     *
     * @return QueryBuilder
     * @throws DatatableException
     */
    private function compileColumnQuery()
    {
        $searchColumns = $this->queryConfiguration->searchColumns();

        foreach ($searchColumns as $i => $col) {
            $column = $this->getColumnFromName($col->columnName());

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
     * Get the requested column configuration from the name of a column
     * @param string $name
     * @return ColumnConfiguration
     * @throws DatatableException when a column is not found
     */
    private function getColumnFromName($name)
    {
        foreach ($this->columnConfiguration as $i => $col) {
            if ($col->getName() == $name) {
                return $col;
            }
        }

        // This exception should never happen. If it does, something is
        // wrong w/ the relationship between searchable columns and the
        // configuration.
        throw new DatatableException("A requested column was not found in the columnConfiguration.");
    }

    /**
     * Get a list of all the column names for the SELECT query.
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
                $this->query->orderBy($order->columnName(), $order->isDescending() ? 'desc' : 'asc');
            }
        }
    }

    /**
     * Will limit a query based on the start and length given
     */
    private function limitQuery()
    {
        $this->query->skip($this->queryConfiguration->start());
        $this->query->limit($this->queryConfiguration->length());
    }
}