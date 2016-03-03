<?php

namespace packages\OpenSkill\Datatable\tests\OpenSkill\Datatable\Versions;


use Mockery;
use OpenSkill\Datatable\Versions\Datatable110Version;
use OpenSkill\Datatable\Versions\Datatable19Version;
use OpenSkill\Datatable\Versions\Version;
use OpenSkill\Datatable\Versions\VersionEngine;
use Symfony\Component\HttpFoundation\Request;

class VersionEngineTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $request = Mockery::mock('Symfony\Component\HttpFoundation\RequestStack');
        $request->shouldReceive('getCurrentRequest')->andReturn();

        $version = Mockery::mock('OpenSkill\Datatable\Versions\Datatable19Version');

        $version->shouldReceive('canParseRequest')->andReturn(true);

        $versionEngine = new VersionEngine([$version]);

        $this->assertTrue($versionEngine->hasVersion());
        $this->assertSame($version, $versionEngine->getVersion());
    }

    public function testManualVersionSet()
    {
        /** @var Version $version */
        $version = Mockery::mock('OpenSkill\Datatable\Versions\Datatable19Version');

        $versionEngine = new VersionEngine([]);

        $versionEngine->setVersion($version);

        $this->assertTrue($versionEngine->hasVersion());
        $this->assertSame($version, $versionEngine->getVersion());
    }

    public function testGivesDatatable19Version()
    {
        $query = new Request([
            'sEcho' => '6',
            'iColumns' => '2',
            'sColumns' => '',
            'iDisplayStart' => '0',
            'iDisplayLength' => '10',
            'mDataProp_0' => 'id',
            'mDataProp_1' => 'name',
            'sSearch' => '',
            'bRegex' => 'false',
            'sSearch_0' => '',
            'bRegex_0' => 'false',
            'bSearchable_0' => 'true',
            'sSearch_1' => '',
            'bRegex_1' => 'false',
            'bSearchable_1' => 'true',
            'iSortCol_0' => '0',
            'sSortDir_0' => 'asc',
            'iSortingCols' => '1',
            'bSortable_0' => 'true',
            'bSortable_1' => 'true',
            '_' => '1456903066843'
        ]);

        $requestStack = Mockery::mock('Symfony\Component\HttpFoundation\RequestStack');
        $requestStack->shouldReceive('getCurrentRequest')->andReturn($query);

        $dt = new Datatable19Version($requestStack);
        $dt2 = new Datatable110Version($requestStack);

        $versionEngine = new VersionEngine([$dt2, $dt]);

        $this->assertTrue($versionEngine->hasVersion());
        $this->assertTrue($versionEngine->getVersion()->canParseRequest());
        $this->assertSame($dt, $versionEngine->getVersion());
    }

}
