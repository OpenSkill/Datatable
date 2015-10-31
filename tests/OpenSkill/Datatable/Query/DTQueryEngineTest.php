<?php

namespace OpenSkill\Datatable\Query;


use Illuminate\Http\Request;
use Mockery;
use OpenSkill\Datatable\Interfaces\DTData;

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
        $engine = new DTQueryEngine($request, [new DT19QueryParser(), new DT110QueryParser()]);

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
        $engine = new DTQueryEngine($request, [new DT19QueryParser(), new DT110QueryParser()]);

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
        $engine = new DTQueryEngine($request, [new DT19QueryParser(), new DT110QueryParser()]);

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
        $engine = new DTQueryEngine($request, [new DT19QueryParser(), new DT110QueryParser()]);

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
        $engine = new DTQueryEngine($request, [new DT19QueryParser(), new DT110QueryParser()]);

        $this->assertFalse($engine->shouldHandle());

        /** @var DTData $data */
        $data = Mockery::mock('OpenSkill\Datatable\Interfaces\DTData');

        $engine->createResponse($data);
    }

    /**
     * Will test if the query engine will call the correct method on the parser.
     */
    public function testResponseCall()
    {
        $request = new Request([]);

        /** @var DTData $data */
        $data = Mockery::mock('OpenSkill\Datatable\Interfaces\DTData');

        /** @var DTQueryParser $parser */
        $parser = Mockery::mock('OpenSkill\Datatable\Query\DTQueryParser');
        $parserMock = Mockery::self();
        $parserMock->shouldReceive('canParse')->with($request)->once()->andReturn(true);
        $parserMock->shouldReceive('respond')->with($data)->once()->andReturn();

        $engine = new DTQueryEngine($request, [$parser]);

        $this->assertTrue($engine->shouldHandle());

        $engine->createResponse($data);
    }


}
