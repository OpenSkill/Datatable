<?php

namespace OpenSkill\Datatable;


use Mockery;

class DatatableTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Will test if a new ColumnComposer will be instantiated correctly
     */
    public function testConstruction()
    {
        $versionEngine = Mockery::mock('OpenSkill\Datatable\Versions\VersionEngine');
        $provider = Mockery::mock('OpenSkill\Datatable\Providers\Provider');

        $viewFactory = Mockery::mock('Illuminate\Contracts\View\Factory');
        $configRepository = Mockery::mock('Illuminate\Contracts\Config\Repository');


        $dt = new Datatable($versionEngine, $viewFactory, $configRepository);
        $clazz = $dt->make($provider);

        $this->assertEquals($provider, $clazz->getProvider());
    }

    /**
     * Will test if a new ColumnComposer will be instantiated correctly
     */
    public function testViewConstruction()
    {
        $versionEngine = Mockery::mock('OpenSkill\Datatable\Versions\VersionEngine');

        $versionEngine->shouldReceive('getVersion')->andReturn();

        $viewFactory = Mockery::mock('Illuminate\Contracts\View\Factory');
        $configRepository = Mockery::mock('Illuminate\Contracts\Config\Repository');

        $dt = new Datatable($versionEngine, $viewFactory, $configRepository);

        $dtv = $dt->view();
    }
}
