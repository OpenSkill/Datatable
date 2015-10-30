<?php

namespace OpenSkill\Datatable\Query;


use Illuminate\Http\Request;
use Mockery;

class DTQueryEngineTest extends \PHPUnit_Framework_TestCase
{

//    /**
//     * @var DTQueryEngine
//     */
//    private $engine;
//
//    /**
//     * @var Mockery\Mock
//     */
//    private $requestMock;
//
//    /**
//     * Will set up the engine and the mocked request
//     */
//    protected function setUp()
//    {
//        $this->requestMock = Mockery::mock('\Illuminate\Http\Request');
//        $this->engine = new DTQueryEngine($this->requestMock);
//    }
//
//    /**
//     * Close the mock engine
//     */
//    public function tearDown()
//    {
//        Mockery::close();
//    }

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

        $this->assertFalse($engine->shouldHandle());
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

        $this->assertFalse($engine->shouldHandle());
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
}
