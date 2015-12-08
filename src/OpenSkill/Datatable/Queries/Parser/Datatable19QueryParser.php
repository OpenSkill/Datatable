<?php

namespace OpenSkill\Datatable\Queries\Parser;


use OpenSkill\Datatable\Columns\ColumnConfiguration;
use OpenSkill\Datatable\Queries\QueryConfiguration;
use OpenSkill\Datatable\Queries\QueryConfigurationBuilder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

class Datatable19QueryParser
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

        // for each column we need to see if there is a search value
        foreach ($columnConfiguration as $i => $c) {
            // increment the index as we are 0 based but data tables is not
            $i++;
            // check if there is something search related
            if ($c->getSearch()->isSearchable() && $query->has("sSearch_" . $i) && !$this->isEmpty($query->get("sSearch_" . $i))) {
                // search for this column is available
                $builder->columnSearch($c->getName(), $query->get("sSearch_" . $i));
            }
            // check if there is something order related
            if ($c->getOrder()->isOrderable() && $query->has("iSortCol_" . $i) && !$this->isEmpty($query->get("iSortCol_" . $i))) {
                // order for this column is available
                $builder->columnOrder($c->getName(), $query->get("sSortDir_" . $i));
            }
        }

        return $builder->build();
    }

    /**
     * Helper function that will check if a variable is empty
     * @param mixed $string
     * @return bool true if empty, false otherwise
     */
    private function isEmpty($string) {
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
     */
    public function getRegex($query, $builder)
    {
        if ($query->has('bRegex')) {
            $builder->searchRegex($query->get('bRegex'));
        }
    }
}