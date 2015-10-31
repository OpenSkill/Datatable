<?php

namespace OpenSkill\Datatable\Query;


use Illuminate\Http\Request;
use OpenSkill\Datatable\Columns\ColumnConfigurationBuilder;

class DT19QueryParserTest extends \PHPUnit_Framework_TestCase
{
    /** @var DT19QueryParser */
    private $parser;

    /**
     * Will set up a the parser to test
     */
    protected function setUp()
    {
        $this->parser = new DT19QueryParser();
    }

    /**
     * Will test if the query parser can parse the request params for datatable 1.9
     * http://legacy.datatables.net/usage/server-side
     *
     */
    public function testParsing()
    {
        // create request
        $request = new Request([
            'sEcho'             => 1,
            'iDisplayStart'     => 1,
            'iDisplayLength'    => 10,
            'iColumns'          => 1,
            'sSearch'           => 'fooBar',
            'bRegex'            => true,
            'bSearchable_1'     => true,
            'sSearch_1'         => 'fooBar_1',
            'bRegex_1'          => true,
            'bSortable_1'       => true,
            'iSortingCols'      => 1,
            'iSortCol_1'        => true,
            'sSortDir_1'        => 'desc',
        ]);

        // create columnconfiguration
        $column = ColumnConfigurationBuilder::create()
            ->name("fooBar")
            ->build();

        $conf = $this->parser->parse($request, [$column]);
    }

    /**
     * Will test if the query parser returns the correct response for datatable 1.9
     * http://legacy.datatables.net/usage/server-side
     *
     */
    public function testResponse()
    {
    }
}
