<?php

namespace OpenSkill\Datatable\Queries\Parser;


use OpenSkill\Datatable\Columns\ColumnConfiguration;
use OpenSkill\Datatable\DatatableException;
use OpenSkill\Datatable\Queries\QueryConfiguration;
use OpenSkill\Datatable\Queries\QueryConfigurationBuilder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

class Datatable19QueryParser extends QueryParser
{

    /**
     * Method to determine if this parser can handle the query parameters. If so then the parser should return true
     * and be able to return a DTQueryConfiguration
     *
     * @param Request $request The current request, that should be investigated
     * @return bool true if the parser is able to parse the query parameters and to return a DTQueryConfiguration
     */
    public function canParse(Request $request)
    {
        return $request->query->has("sEcho");
    }

    /**
     * Method that should parse the request and return a DTQueryConfiguration
     *
     * @param Request $request The current request that should be investigated
     * @param ColumnConfiguration[] $columnConfiguration The configuration of the columns
     * @return QueryConfiguration the configuration the provider can use to prepare the data
     */
    public function parse(Request $request, array $columnConfiguration)
    {
        $query = $request->query;
        $builder = QueryConfigurationBuilder::create();

        $this->getDrawCall($query, $builder);

        $this->getStart($query, $builder);

        $this->getLength($query, $builder);

        $this->getSearch($query, $builder);

        $this->getRegex($query, $builder);

        $this->getSearchColumns($query, $builder, $columnConfiguration);

        return $builder->build();
    }

    /**
     * Helper function that will check if a variable is empty
     * @param mixed $string
     * @return bool true if empty, false otherwise
     */
    private function isEmpty($string)
    {
        return empty($string);
    }

    /**
     * @param ParameterBag $query
     * @param QueryConfigurationBuilder $builder
     */
    public function getDrawCall($query, $builder)
    {
        if ($query->has('sEcho')) {
            $builder->drawCall($query->get('sEcho'));
        }
    }

    /**
     * @param ParameterBag $query
     * @param QueryConfigurationBuilder $builder
     */
    public function getStart($query, $builder)
    {
        if ($query->has('iDisplayStart')) {
            $builder->start($query->get('iDisplayStart'));
        }
    }

    /**
     * @param ParameterBag $query
     * @param QueryConfigurationBuilder $builder
     */
    public function getLength($query, $builder)
    {
        if ($query->has('iDisplayLength')) {
            $builder->length($query->get('iDisplayLength'));
        }
    }

    /**
     * @param ParameterBag $query
     * @param QueryConfigurationBuilder $builder
     */
    public function getSearch($query, $builder)
    {
        if ($query->has('sSearch') && !$this->isEmpty($query->get('sSearch'))) {
            $builder->searchValue($query->get('sSearch'));
        }
    }

    /**
     * @param ParameterBag $query
     * @param QueryConfigurationBuilder $builder
     * @param ColumnConfiguration[] $columnConfiguration
     */
    public function getSearchColumns($query, $builder, array $columnConfiguration)
    {
        // for each column we need to see if there is a search value
        foreach ($columnConfiguration as $i => $c) {
            // check if there is something search related
            if ($c->getSearch()->isSearchable() && $query->has("sSearch_" . $i) && !$this->isEmpty($query->get("sSearch_" . $i))) {
                // search for this column is available
                $builder->columnSearch($c->getName(), $query->get("sSearch_" . $i));
            }
        }

        if ($query->has('iSortingCols')) {
            $this->determineSortableColumns($query, $builder, $columnConfiguration);
        }
    }

    /**
     * @param ParameterBag $query
     * @param QueryConfigurationBuilder $builder
     * @param ColumnConfiguration[] $columnConfiguration
     * @throws DatatableException when a column for sorting is out of bounds
     */
    private function determineSortableColumns($query, $builder, array $columnConfiguration)
    {
        $columns = intval($query->get('iSortingCols'));

        for($i = 0; $i < $columns+1; $i++) {
            if ($query->has("iSortCol_" . $i) && !$this->isEmpty($query->get("iSortCol_" . $i))) {
                $columnPosition = intval($query->get("iSortCol_" . $i));

                if (!isset($columnConfiguration[$columnPosition])) {
                    throw new DatatableException('The column requested for ordering does not exist');
                }

                $c = $columnConfiguration[$columnPosition];

                if ($c->getOrder()->isOrderable()) {
                    $builder->columnOrder($c->getName(), $query->get("sSortDir_" . $i));
                }
            }
        }
    }

    /**
     * @param ParameterBag $query
     * @param QueryConfigurationBuilder $builder
     */
    public function getRegex($query, $builder)
    {
        if ($query->has('bRegex')) {
            $builder->searchRegex($query->get('bRegex'));
        }
    }
}
