<?php

namespace packages\OpenSkill\Datatable\tests\OpenSkill\Datatable\Versions;

use Illuminate\Http\Request;
use Mockery;
use OpenSkill\Datatable\Versions\Datatable19Version;

class Datatable19VersionTest extends \PHPUnit_Framework_TestCase
{

    /** @var Request */
    private $request;

    /**
     * Will set up the mock request
     */
    public function setUp()
    {
        $this->request = Mockery::mock('Illuminate\Http\Request');
    }

    /**
     * Will test if the 1.9 version can be constructed and behaves correctly
     */
    public function testManualConstruction()
    {
        $parser = Mockery::mock('OpenSkill\Datatable\Queries\Parser\QueryParser');
        $response = Mockery::mock('OpenSkill\Datatable\Responses\ResponseCreator');
        $view = Mockery::mock('OpenSkill\Datatable\Views\ViewCreator');

        $version = new Datatable19Version($this->request, $parser, $response, $view);

        $this->assertEquals($parser, $version->queryParser());
        $this->assertEquals($response, $version->responseCreator());
        $this->assertEquals($view, $version->viewCreator());
    }

    public function testAutomaticContstruction()
    {
        $version = new Datatable19Version($this->request);

        $this->assertNotNull($version->queryParser());
        $this->assertNotNull($version->responseCreator());
        $this->assertNotNull($version->viewCreator());
    }



}
