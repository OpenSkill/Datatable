<?php

namespace OpenSkill\Datatable\Queries\Parser;


use OpenSkill\Datatable\Columns\ColumnConfigurationBuilder;
use OpenSkill\Datatable\Columns\Orderable\Orderable;
use OpenSkill\Datatable\Columns\Searchable\Searchable;
use Symfony\Component\HttpFoundation\Request;

class Datatable110QueryParserTest extends \PHPUnit_Framework_TestCase
{
    /** @var Datatable110QueryParser */
    private $parser;

    /** @var Request */
    private $request;

    public function testCanParse()
    {
        $this->assertTrue($this->parser->canParse($this->request));
    }

    /**
     * Will set up a the parser to test
     */
    protected function setUp()
    {
        // create request
        $this->request = new Request([
            'draw' => 13,
            'start' => 11,
            'length' => 103,
            'search' => [
                'value' => 'fooBar',
                'regex' => true
            ],
            'order' => [
                0 => [
                    'column' => 0,
                    'dir' => 'asc'
                ],
            ],
            'columns' => [
                0 => [
                    'search' => [
                        'value' => 'fooBar',
                        'regex' => true,
                    ],
                ]
            ],
        ]);

        $this->parser = new Datatable110QueryParser();
    }

    /**
     * Will test if the query parser can parse the request params for datatable 1.10
     * http://datatables.net/examples/data_sources/server_side.html
     *
     */
    public function testCorrectParsing()
    {
        // create columnconfiguration
        $column = ColumnConfigurationBuilder::create()
            ->name("fooBar")
            ->build();

        $conf = $this->parser->parse($this->request, [$column]);

        $this->assertSame(13, $conf->drawCall());
        $this->assertSame(11, $conf->start());
        $this->assertSame(103, $conf->length());
        $this->assertSame('fooBar', $conf->searchValue());
        $this->assertTrue($conf->isGlobalRegex());


        // assert column search
        $this->assertCount(1, $conf->searchColumns());
        $def = $conf->searchColumns()['fooBar'];
        $this->assertSame("fooBar", $def->searchValue());
        $this->assertSame("fooBar", $def->columnName());

        // assert column order
        $this->assertCount(1, $conf->orderColumns());
        $def = $conf->orderColumns()[0];
        $this->assertSame('fooBar', $def->columnName());
        $this->assertTrue($def->isAscending());
    }

    /**
     * Will test if the query parser will ignore search and order advise if the columns forbid them
     *
     */
    public function testWrongParsing()
    {
        // create columnconfiguration
        $column = ColumnConfigurationBuilder::create()
            ->name("fooBar")
            ->orderable(Orderable::NONE())
            ->searchable(Searchable::NONE())
            ->build();

        $conf = $this->parser->parse($this->request, [$column]);

        // assert column search
        $this->assertCount(0, $conf->searchColumns());

        // assert column order
        $this->assertCount(0, $conf->orderColumns());
    }

    /**
     * Will test that the sorting order from the query can be used to sort the data in the correct order.
     */
    public function testSortingOrder()
    {
        $this->request = new Request([
            'draw' => 13,
            'start' => 0,
            'length' => 10,
            'search' => [
                'value' => 'fooBar',
                'regex' => true
            ],
            'order' => [
                0 => [
                    'column' => 0,
                    'dir' => 'desc'
                ],
                1 => [
                    'column' => 1,
                    'dir' => 'asc'
                ],
            ],
            'columns' => [
                0 => [
                    'search' => [
                        'value' => 'foobar',
                        'regex' => true,
                    ],
                ]
            ],
        ]);

        $this->parser = new Datatable110QueryParser($this->request);

        $column = ColumnConfigurationBuilder::create()
            ->name("id")
            ->build();
        $column1 = ColumnConfigurationBuilder::create()
            ->name("name")
            ->build();

        $conf = $this->parser->parse($this->request, [$column, $column1]);

        // assert column order
        $this->assertCount(2, $conf->orderColumns());
        $def = $conf->orderColumns()[0];
        $this->assertSame('id', $def->columnName());
        $this->assertFalse($def->isAscending());
    }

    public function testSortingOrder2()
    {
        $this->request = new Request([
            'draw' => 13,
            'start' => 0,
            'length' => 10,
            'search' => [
                'value' => 'fooBar',
                'regex' => true
            ],
            'order' => [
                0 => [
                    'column' => 1,
                    'dir' => 'asc'
                ],
                1 => [
                    'column' => 0,
                    'dir' => 'asc'
                ],
            ],
            'columns' => [
                0 => [
                    'search' => [
                        'value' => 'foobar',
                        'regex' => true,
                    ],
                ]
            ],
        ]);

        $this->parser = new Datatable110QueryParser($this->request);

        $column = ColumnConfigurationBuilder::create()
            ->name("id")
            ->build();
        $column1 = ColumnConfigurationBuilder::create()
            ->name("name")
            ->build();

        $conf = $this->parser->parse($this->request, [$column, $column1]);

        // assert column order
        $this->assertCount(2, $conf->orderColumns());
        $def = $conf->orderColumns()[0];
        $this->assertSame('name', $def->columnName());
        $this->assertTrue($def->isAscending());
    }

    /**
     * Will test that an empty search will not trigger a search.
     */
    public function testEmptySearch()
    {
        $this->request = new Request([
            'draw' => 13,
            'start' => 11,
            'length' => 103,
            'search' => [
                'value' => '',
                'regex' => true
            ],
            'order' => [
                0 => [
                    'column' => 0,
                    'dir' => 'asc'
                ],
            ],
            'columns' => [
                0 => [
                    'search' => [
                        'value' => '',
                        'regex' => true,
                    ],
                ]
            ],
        ]);

        $this->parser = new Datatable110QueryParser($this->request);

        $column = ColumnConfigurationBuilder::create()
            ->name("id")
            ->build();

        $conf = $this->parser->parse($this->request, [$column]);

        // assert column order
        $this->assertFalse($conf->isGlobalSearch());
        $this->assertCount(0, $conf->searchColumns());
        $this->assertFalse($conf->isColumnSearch());
    }

    public function testArrayHasValidKeys()
    {
        $requestParameters = [
            'draw' => 13,
            'start' => 11,
            'length' => 103,
            'order' => [
                0 => [
                    'column' => 0,
                    'dir' => 'asc'
                ],
            ],
        ];

        $this->request = new Request($requestParameters);
        $this->parser = new Datatable110QueryParser($this->request);

        $column = ColumnConfigurationBuilder::create()
            ->name("id")
            ->build();

        $conf = $this->parser->parse($this->request, [$column]);
        $this->assertFalse($conf->isGlobalSearch());

        $requestParameters['search'] = [
            'string' => 'something'
        ];

        $this->request = new Request($requestParameters);
        $this->parser = new Datatable110QueryParser($this->request);
        $conf = $this->parser->parse($this->request, [$column]);
        $this->assertFalse($conf->isGlobalSearch());

        $requestParameters['search'] = [
            'string' => 'something',
            'regex' => true
        ];

        $this->request = new Request($requestParameters);
        $this->parser = new Datatable110QueryParser($this->request);
        $conf = $this->parser->parse($this->request, [$column]);
        $this->assertFalse($conf->isGlobalSearch());
    }
}
