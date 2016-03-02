<?php

namespace packages\OpenSkill\Datatable\tests\OpenSkill\Datatable\Versions;

use Illuminate\Support\Collection;
use Mockery;
use OpenSkill\Datatable\Data\ResponseData;
use OpenSkill\Datatable\Versions\Datatable19Version;
use OpenSkill\Datatable\Columns\ColumnConfigurationBuilder;
use Symfony\Component\HttpFoundation\Request;

class Datatable19VersionTest extends \PHPUnit_Framework_TestCase
{

    /** @var Request */
    private $request;

    /** @var Datatable19Version */
    private $version;

    public function setUp()
    {
        $this->request = new Request([
            'sEcho' => 13,
            'iDisplayStart' => 11,
            'iDisplayLength' => 103,
            'iColumns' => 1, // will be ignored, the column number is already set on the server side
            'sSearch' => 'fooBar',
            'bRegex' => true,
            'bSearchable_1' => true, // will be ignored, the configuration is already set on the server side
            'sSearch_1' => 'fooBar_1',
            'bRegex_1' => true, // will be ignored, the configuration is already set on the server side
            'bSortable_1' => true, // will be ignored, the configuration is already set on the server side
            'iSortingCols' => 1, // will be ignored, the configuration is already set on the server side
            'iSortCol_1' => 0,
            'sSortDir_1' => 'desc',
            'iSortCol_2' => 1,
            'sSortDir_2' => 'desc',
        ]);

        $requestStack = Mockery::mock('Symfony\Component\HttpFoundation\RequestStack');
        $requestStack->shouldReceive('getCurrentRequest')->andReturn($this->request);

        $this->version = new Datatable19Version($requestStack);
    }

    public function testCanParseRequest()
    {
        $this->assertTrue($this->version->canParseRequest());
    }

    public function testParse()
    {
        $column = ColumnConfigurationBuilder::create()
            ->name("id")
            ->build();

        $column1 = ColumnConfigurationBuilder::create()
            ->name("name")
            ->build();

        $cc = $this->version->parseRequest([$column, $column1]);
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
        $this->version = new Datatable19Version($requestStack);
        $this->version->canParseRequest();
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testNull2Request()
    {
        $requestStack = Mockery::mock('Symfony\Component\HttpFoundation\RequestStack');
        $requestStack->shouldReceive('getCurrentRequest')->andReturnNull();
        $this->version = new Datatable19Version($requestStack);
        $this->version->parseRequest([]);
    }

    public function testViewString()
    {
        $this->assertNotNull($this->version->tableView());
        $this->assertNotNull($this->version->scriptView());
    }


}
