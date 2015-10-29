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
        $engine = new DTQueryEngine($request);

        $this->assertFalse($engine->shouldHandle());
    }
}
