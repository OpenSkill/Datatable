<?php

namespace packages\OpenSkill\Datatable\tests\OpenSkill\Datatable\Versions;


use Mockery;
use OpenSkill\Datatable\Versions\Datatable19Version;
use OpenSkill\Datatable\Versions\Version;
use OpenSkill\Datatable\Versions\VersionEngine;

class VersionEngineTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $version = Mockery::mock('OpenSkill\Datatable\Versions\Datatable19Version');
        $queryParser = Mockery::mock('OpenSkill\Datatable\Queries\Parser\QueryParser');

        $version->shouldReceive('queryParser')->andReturn($queryParser);
        $queryParser->shouldReceive('canParse')->andReturn(true);

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


}
