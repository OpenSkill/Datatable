<?php

namespace OpenSkill\Datatable\Versions;


use Mockery;
use Illuminate\Support\Collection;
use OpenSkill\Datatable\Data\ResponseData;
use Symfony\Component\HttpFoundation\Request;

class Datatable110VersionTest extends \PHPUnit_Framework_TestCase
{
    /** @var Request */
    private $request;

    /** @var Datatable110Version */
    private $version;

    public function setUp()
    {
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
        $requestStack = Mockery::mock('Symfony\Component\HttpFoundation\RequestStack');
        $requestStack->shouldReceive('getCurrentRequest')->andReturn($this->request);
        $this->version = new Datatable110Version($requestStack);
    }

    public function testCanParseRequest()
    {
        $this->assertTrue($this->version->canParseRequest());
    }

    public function testParse()
    {
        $cc = $this->version->parseRequest([]);
        $this->assertNotNull($cc);

        $rsp = $this->version->createResponse(new ResponseData(new Collection([]), 123), $cc, []);

        $this->assertNotNull($rsp);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testNullRequest()
    {
        $requestStack = Mockery::mock('Symfony\Component\HttpFoundation\RequestStack');
        $requestStack->shouldReceive('getCurrentRequest')->andReturnNull();
        $this->version = new Datatable110Version($requestStack);
        $this->version->canParseRequest();
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testNull2Request()
    {
        $requestStack = Mockery::mock('Symfony\Component\HttpFoundation\RequestStack');
        $requestStack->shouldReceive('getCurrentRequest')->andReturnNull();
        $this->version = new Datatable110Version($requestStack);
        $this->version->parseRequest([]);
    }

    public function testViewString()
    {
        $this->assertNotNull($this->version->tableView());
        $this->assertNotNull($this->version->scriptView());
    }
}
