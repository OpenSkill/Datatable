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

        $dt = new Datatable($versionEngine);
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

        $dt = new Datatable($versionEngine);

        $dtv = $dt->view();
    }
}
