<?php

namespace OpenSkill\Datatable\Queries\Parser;


use OpenSkill\Datatable\Columns\ColumnConfiguration;
use OpenSkill\Datatable\Queries\QueryConfiguration;
use OpenSkill\Datatable\Queries\QueryConfigurationBuilder;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

class Datatable110QueryParser extends QueryParser
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
        return $request->query->has("draw");
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

        $this->getOrder($query, $builder, $columnConfiguration);

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
     * Helper function that will check if an array key exists
     * @param mixed $array
     * @param string $key key to check
     * @return bool true if array & exists, false otherwise
     */
    private function isArrayAndHasKey($array, $key)
    {
        if (!is_array($array)) {
            return false;
        }

        if (array_key_exists($key, $array)) {
            return true;
        }

        return false;
    }

    /**
     * @param ParameterBag $query
     * @param QueryConfigurationBuilder $builder
     */
    public function getDrawCall($query, $builder)
    {
        if ($query->has('draw')) {
            $builder->drawCall($query->get('draw'));
        }
    }

    /**
     * @param ParameterBag $query
     * @param QueryConfigurationBuilder $builder
     */
    public function getStart($query, $builder)
    {
        if ($query->has('start')) {
            $builder->start($query->get('start'));
        }
    }

    /**
     * @param ParameterBag $query
     * @param QueryConfigurationBuilder $builder
     */
    public function getLength($query, $builder)
    {
        if ($query->has('length')) {
            $builder->length($query->get('length'));
        }
    }

    /**
     * @param ParameterBag $query
     * @param QueryConfigurationBuilder $builder
     */
    public function getSearch($query, $builder)
    {
        $search = $query->get('search');

        if ($this->isArrayAndHasKey($search, 'value')) {
            $builder->searchValue($search['value']);
        }
    }

    /**
     * @param ParameterBag $query
     * @param QueryConfigurationBuilder $builder
     */
    public function getSearchColumns($query, $builder, array $columnConfiguration)
    {
        // for each column we need to see if there is a search value
        $columns = $query->get('columns');

        foreach ($columnConfiguration as $i => $c) {
            // check if there is something search related
            if (!isset($columns[$i])) {
                continue;
            }

            $this->addColumnSearchToBuilderIfRequested($columns, $builder, $c, $i);
        }
    }

    /**
     * @param array $columns incoming column request
     * @param QueryConfigurationBuilder $builder
     * @param ColumnConfiguration $column
     * @param integer $position position of the column in the columnConfiguration loop
     */
    private function addColumnSearchToBuilderIfRequested($columns, $builder, $column, $position)
    {
        if ($column->getSearch()->isSearchable()) {
            // search for this column is available
            $value = $columns[$position]['search']['value'];

            if (!$this->isEmpty($value)) {
                $builder->columnSearch($column->getName(), $value);
            }
        }
    }

    /**
     * @param ParameterBag $query
     * @param QueryConfigurationBuilder $builder
     */
    public function getRegex($query, $builder)
    {
        $search = $query->get('search');

        if ($this->isArrayAndHasKey($search, 'regex')) {
            $builder->searchRegex($search['regex']);
        }
    }

    /**
     * @param ParameterBag $query
     * @param QueryConfigurationBuilder $builder
     * @param ColumnConfiguration[] $columnConfiguration
     */
    private function getOrder(ParameterBag $query, QueryConfigurationBuilder $builder, array $columnConfiguration)
    {
        //loop over the order
        if(($query->has('order'))) {
            $order = $query->get('order');
            foreach($order as $i => $config) {
                if(array_key_exists($config['column'], $columnConfiguration)) {
                    $column = $columnConfiguration[$config['column']];
                    if($column->getOrder()->isOrderable()) {
                        $builder->columnOrder($column->getName(), $config['dir']);
                    }
                }
            }
        }
    }
}
