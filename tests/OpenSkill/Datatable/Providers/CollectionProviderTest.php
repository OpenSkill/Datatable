<?php

namespace packages\OpenSkill\Datatable\tests\OpenSkill\Datatable\Providers;

use Illuminate\Support\Collection;
use OpenSkill\Datatable\Columns\ColumnConfigurationBuilder;
use OpenSkill\Datatable\Providers\CollectionProvider;
use OpenSkill\Datatable\Queries\QueryConfigurationBuilder;

/**
 * Class CollectionProviderTest
 * @package packages\OpenSkill\Datatable\tests\OpenSkill\Datatable\Providers
 */
class CollectionProviderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testProcessWithNoSetup()
    {
        $provider = new CollectionProvider(new Collection());
        $provider->process([]);
    }

    public function testProcess()
    {
        $data = [
            ['id' => 1, 'name' => 'foo'],
            ['id' => 2, 'name' => 'foo2'],
        ];

        $queryConfiguration = QueryConfigurationBuilder::create()
            ->start(0)
            ->length(2)
            ->drawCall(1)
            ->columnOrder('name', 'desc')
            ->build();

        $columnConfiguration = ColumnConfigurationBuilder::create()
            ->name('name')
            ->build();

        $provider = new CollectionProvider(new Collection($data));
        $provider->prepareForProcessing($queryConfiguration);
        $data = $provider->process([$columnConfiguration]);

        $this->assertSame(2, $data->count());

        $first = $data->first();
        $this->assertSame(['name' => 'foo'], $first);
    }
}
