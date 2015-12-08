<?php

namespace OpenSkill\Datatable;


use Mockery;

class DatatableServiceTest extends \PHPUnit_Framework_TestCase
{

    public function testMethods()
    {
        $request = Mockery::mock('Illuminate\Http\Request');
        $provider = Mockery::mock('OpenSkill\Datatable\Providers\Provider');
        $provider->shouldReceive('prepareForProcessing')->andReturn();
        $provider->shouldReceive('process')->andReturn();

        $version = Mockery::mock('OpenSkill\Datatable\Versions\Version');

        $versionEngine = Mockery::mock('OpenSkill\Datatable\Versions\VersionEngine');
        $versionEngine->shouldReceive('hasVersion')->andReturn(true);
        $versionEngine->shouldReceive('setVersion')->andReturn();
        $versionEngine->shouldReceive('getVersion')->andReturn($version);

        $queryConfig = Mockery::mock('OpenSkill\Datatable\Queries\QueryConfiguration');

        $version->shouldReceive('queryParser->parse')->andReturn($queryConfig);
        $version->shouldReceive('responseCreator->createResponse')->andReturn();

        $dts = new DatatableService($request, $provider, [], $versionEngine);

        $dts->setVersion($version);

        $dts->shouldHandle();

        $dts->handleRequest();

        $dts->view();
    }
}
