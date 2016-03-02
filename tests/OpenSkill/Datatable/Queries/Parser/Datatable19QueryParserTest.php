<?php

namespace OpenSkill\Datatable\Queries\Parser;


use OpenSkill\Datatable\Columns\ColumnConfigurationBuilder;
use OpenSkill\Datatable\Columns\Orderable\Orderable;
use OpenSkill\Datatable\Columns\Searchable\Searchable;
use OpenSkill\Datatable\DatatableException;
use OpenSkill\Datatable\Queries\Parser\Datatable19QueryParser;
use Symfony\Component\HttpFoundation\Request;

class Datatable19QueryParserTest extends \PHPUnit_Framework_TestCase
{
    /** @var Datatable19QueryParser */
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
            'sEcho' => 13,
            'iDisplayStart' => 11,
            'iDisplayLength' => 103,
            'iColumns' => 1, // will be ignored, the column number is already set on the server side
            'sSearch' => 'fooBar',
            'bRegex' => true,
            'bSearchable_1' => true, // will be ignored, the configuration is already set on the server side
            'sSearch_0' => 'fooBar_1',
            'bRegex_0' => true, // will be ignored, the configuration is already set on the server side
            'bSortable_0' => true, // will be ignored, the configuration is already set on the server side
            'iSortingCols' => 1,
            'iSortCol_0' => 0,
            'sSortDir_0' => 'desc',
        ]);

        $this->parser = new Datatable19QueryParser();
    }

    /**
     * Will test if the query parser can parse the request params for datatable 1.9
     * http://legacy.datatables.net/usage/server-side
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
        $this->assertSame("fooBar_1", $def->searchValue());
        $this->assertSame("fooBar", $def->columnName());

        // assert column order
        $this->assertCount(1, $conf->orderColumns());
        $def = $conf->orderColumns()[0];
        $this->assertSame('fooBar', $def->columnName());
        $this->assertFalse($def->isAscending());
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
            'sEcho' => 13,
            'iDisplayStart' => 11,
            'iDisplayLength' => 103,
            'iColumns' => 1, // will be ignored, the column number is already set on the server side
            'sSearch' => 'fooBar',
            'bRegex' => true,
            'bSearchable_0' => true, // will be ignored, the configuration is already set on the server side
            'sSearch_0' => 'fooBar_1',
            'bRegex_0' => true, // will be ignored, the configuration is already set on the server side
            'bSortable_0' => true, // will be ignored, the configuration is already set on the server side
            'iSortingCols' => 2,
            'iSortCol_0' => 1,
            'sSortDir_0' => 'desc',
            'iSortCol_1' => 0,
            'sSortDir_1' => 'desc',
        ]);

        $this->parser = new Datatable19QueryParser($this->request);

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
        $this->assertFalse($def->isAscending());
    }

    /**
     * Will test that the sorting order from the query can be used to sort the data in the correct order.
     */
    public function testSortingOrder2()
    {
        $this->sortingOrderGeneration();
    }

    /**
     * Will test that the sorting order without all the columns in the configuration correctly throws an exception
     * @expectedException \OpenSkill\Datatable\DatatableException
     */
    public function testSortingOrder3()
    {
        $this->sortingOrderGeneration(false);
    }

    /**
     * The real testSortingOrder2 & testSortingOrder3 test
     * @see testSortingOrder2
     * @see testSortingOrder3
     * @param bool $includeSecondColumnInConfiguration
     */
    private function sortingOrderGeneration($includeSecondColumnInConfiguration = true)
    {
        $this->request = new Request([
            'sEcho' => 13,
            'iDisplayStart' => 11,
            'iDisplayLength' => 103,
            'iColumns' => 1, // will be ignored, the column number is already set on the server side
            'sSearch' => 'fooBar',
            'bRegex' => true,
            'bSearchable_0' => true, // will be ignored, the configuration is already set on the server side
            'sSearch_0' => 'fooBar_1',
            'bRegex_0' => true, // will be ignored, the configuration is already set on the server side
            'bSortable_0' => true, // will be ignored, the configuration is already set on the server side
            'iSortingCols' => 2,
            'iSortCol_1' => 1,
            'sSortDir_1' => 'desc',
        ]);

        $this->parser = new Datatable19QueryParser($this->request);

        $columns = [];
        $columns[] = ColumnConfigurationBuilder::create()
            ->name("id")
            ->build();

        if ($includeSecondColumnInConfiguration) {
            $columns[] = ColumnConfigurationBuilder::create()
                ->name("name")
                ->build();
        }

        $conf = $this->parser->parse($this->request, $columns);

        // assert column order
        if (!$includeSecondColumnInConfiguration) {
            $this->assertCount(1, $conf->orderColumns());
            $def = $conf->orderColumns()[0];
            $this->assertSame('name', $def->columnName());
            $this->assertFalse($def->isAscending());
        }
    }

    /**
     * Will test that an empty search will not trigger a search.
     */
    public function testEmptySearch()
    {
        $this->request = new Request([
            'sEcho' => 13,
            'iDisplayStart' => 11,
            'iDisplayLength' => 103,
            'sSearch' => '',
            'sSearch_0' => ''
        ]);

        $this->parser = new Datatable19QueryParser($this->request);

        $column = ColumnConfigurationBuilder::create()
            ->name("id")
            ->build();

        $conf = $this->parser->parse($this->request, [$column]);

        // assert column order
        $this->assertFalse($conf->isGlobalSearch());
        $this->assertCount(0, $conf->searchColumns());
        $this->assertFalse($conf->isColumnSearch());
    }

}

