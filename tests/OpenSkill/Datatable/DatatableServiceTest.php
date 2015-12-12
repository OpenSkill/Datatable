<?php

namespace OpenSkill\Datatable;


use Mockery;
use OpenSkill\Datatable\Data\ResponseData;

class DatatableServiceTest extends \PHPUnit_Framework_TestCase
{

    public function testMethods()
    {
        $rspData = Mockery::mock('OpenSkill\Datatable\Data\ResponseData');

        $provider = Mockery::mock('OpenSkill\Datatable\Providers\Provider');
        $provider->shouldReceive('prepareForProcessing')->andReturn();

        $provider->shouldReceive('process')->andReturn($rspData);

        $version = Mockery::mock('OpenSkill\Datatable\Versions\Version');

        $versionEngine = Mockery::mock('OpenSkill\Datatable\Versions\VersionEngine');
        $versionEngine->shouldReceive('hasVersion')->andReturn(true);
        $versionEngine->shouldReceive('setVersion')->andReturn();
        $versionEngine->shouldReceive('getVersion')->andReturn($version);

        $queryConfig = Mockery::mock('OpenSkill\Datatable\Queries\QueryConfiguration');

        $version->shouldReceive('parseRequest')->andReturn($queryConfig);
        $version->shouldReceive('createResponse')->andReturn();
        $version->shouldReceive('canParseRequest')->andReturn(true);
        $version->shouldReceive('tableView')->andReturn("fooBar");
        $version->shouldReceive('scriptView')->andReturn("fooBar");

        $viewFactory = Mockery::mock('Illuminate\Contracts\View\Factory');
        $configRepository = Mockery::mock('Illuminate\Contracts\Config\Repository');
        $configRepository->shouldReceive('get')->andReturn("fooBar");

        $dts = new DatatableService($provider, [], $versionEngine, $viewFactory, $configRepository);

        $dts->setVersion($version);

        $dts->shouldHandle();

        $dts->handleRequest();

        $dts->view();
    }
}
