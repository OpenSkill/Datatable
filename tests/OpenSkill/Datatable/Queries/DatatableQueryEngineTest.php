<?php

namespace OpenSkill\Datatable\Queries;

use Illuminate\Http\Request;
use Mockery;
use OpenSkill\Datatable\Interfaces\Data;
use OpenSkill\Datatable\Queries\Parser\Datatable110QueryParser;
use OpenSkill\Datatable\Queries\Parser\Datatable19QueryParser;
use OpenSkill\Datatable\Queries\Parser\QueryParser;

class DTQueryEngineTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Close the mock engine
     */
    public function tearDown()
    {
        Mockery::close();
    }

    /**
     * Will test if the DTQueryEngine behaves correctly when a request should not be handled
     */
    public function testNormalQuery()
    {
        $request = new Request([
            "foo" => "bar"
        ]);
        $engine = new QueryEngine($request, [new Datatable19QueryParser($request), new Datatable110QueryParser($request)]);

        $this->assertFalse($engine->shouldHandle());
    }

    /**
     * Will test if the DTQueryEngine behaves correctly when a request should be handled by the 1.9 parser
     */
    public function test_1_9_Query()
    {
        $request = new Request([
            "sEcho" => 1
        ]);
        $engine = new QueryEngine($request, [new Datatable19QueryParser($request), new Datatable110QueryParser($request)]);

        $this->assertTrue($engine->shouldHandle());
    }

    /**
     * Will test if the DTQueryEngine behaves correctly when a request should be handled by the 1.10 parser
     */
    public function test_1_10_Query()
    {
        $request = new Request([
            "draw" => 1
        ]);
        $engine = new QueryEngine($request, [new Datatable19QueryParser($request), new Datatable110QueryParser($request)]);

        $this->assertTrue($engine->shouldHandle());
    }

    /**
     * Will test if the DTQueryEngine behaves correctly when a request should not be handled by the 1.9 or the 1.10 parser
     */
    public function test_1_9_X_1_10_Query()
    {
        $request = new Request([
            "sEcho" => 1,
            "draw"  => 2
        ]);
        $engine = new QueryEngine($request, [new Datatable19QueryParser($request), new Datatable110QueryParser($request)]);

        $this->assertFalse($engine->shouldHandle());
    }

    /**
     * Will test if the query engine will throw an exception when no parser was set.
     * @expectedException \InvalidArgumentException
     */
    public function testResponseException()
    {
        $request = new Request([
            "sEcho" => 1,
            "draw"  => 2
        ]);
        $engine = new QueryEngine($request, [new Datatable19QueryParser($request), new Datatable110QueryParser($request)]);

        $this->assertFalse($engine->shouldHandle());

        /** @var Data $data */
        $data = Mockery::mock('OpenSkill\Datatable\Interfaces\Data');

        $engine->createResponse($data);
    }

    /**
     * Will test if the query engine will call the correct method on the parser.
     */
    public function testResponseCall()
    {
        $request = new Request([]);

        /** @var Data $data */
        $data = Mockery::mock('OpenSkill\Datatable\Interfaces\Data');

        /** @var QueryParser $parser */
        $parser = Mockery::mock('OpenSkill\Datatable\Query\QueryParser');
        $parserMock = Mockery::self();
        $parserMock->shouldReceive('canParse')->with($request)->once()->andReturn(true);
        $parserMock->shouldReceive('respond')->with($data)->once()->andReturn();

        $engine = new QueryEngine($request, [$parser]);

        $this->assertTrue($engine->shouldHandle());

        $engine->createResponse($data);
    }


}
