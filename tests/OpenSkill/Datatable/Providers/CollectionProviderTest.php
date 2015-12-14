<?php

namespace packages\OpenSkill\Datatable\tests\OpenSkill\Datatable\Providers;

use Illuminate\Support\Collection;
use OpenSkill\Datatable\Columns\ColumnConfigurationBuilder;
use OpenSkill\Datatable\Columns\ColumnOrder;
use OpenSkill\Datatable\Columns\Searchable\Searchable;
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
        $provider->process();
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
        $provider->prepareForProcessing($queryConfiguration, [$columnConfiguration]);
        $data = $provider->process();

        $this->assertSame(2, $data->data()->count());

        $first = $data->data()->first();
        $this->assertSame(['name' => 'foo2'], $first);
    }

    /**
     * Will test if a global custom search will return all results
     */
    public function testGlobalSearch()
    {
        $data = [
            ['id' => 1, 'name' => 'foo'],
            ['id' => 2, 'name' => 'foo2'],
        ];

        $queryConfiguration = QueryConfigurationBuilder::create()
            ->start(0)
            ->length(2)
            ->drawCall(1)
            ->searchValue("foo2")
            ->build();

        $columnConfiguration = ColumnConfigurationBuilder::create()
            ->name('name')
            ->build();

        $provider = new CollectionProvider(new Collection($data));

        $provider->prepareForProcessing($queryConfiguration, [$columnConfiguration]);
        $data = $provider->process();

        $this->assertSame(1, $data->data()->count());
    }

    /**
     * Will test if a global custom search will return all results
     */
    public function testDefaultGlobalSearch()
    {
        $data = [
            ['id' => 1, 'name' => 'foo'],
            ['id' => 2, 'name' => 'foo2'],
        ];

        $queryConfiguration = QueryConfigurationBuilder::create()
            ->start(0)
            ->length(2)
            ->drawCall(1)
            ->searchValue("fooBar")
            ->build();

        $columnConfiguration = ColumnConfigurationBuilder::create()
            ->name('name')
            ->build();

        $provider = new CollectionProvider(new Collection($data));
        $provider->search(function ($data) {
            return false;
        });
        $provider->prepareForProcessing($queryConfiguration, [$columnConfiguration]);
        $data = $provider->process();

        $this->assertSame(0, $data->data()->count());
    }

    /**
     * Will test if a custom global search will return no results
     */
    public function testGlobalSearch2()
    {
        $data = [
            ['id' => 1, 'name' => 'foo'],
            ['id' => 2, 'name' => 'bar'],
        ];

        $queryConfiguration = QueryConfigurationBuilder::create()
            ->start(0)
            ->length(2)
            ->searchValue('foo')
            ->drawCall(1)
            ->build();

        $columnConfiguration = ColumnConfigurationBuilder::create()
            ->name('name')
            ->build();

        $provider = new CollectionProvider(new Collection($data));
        $provider->search(function ($data, $search) {
            return $data['name'] == $search;
        });

        $provider->prepareForProcessing($queryConfiguration, [$columnConfiguration]);
        $data = $provider->process();

        $this->assertSame(1, $data->data()->count());
    }

    /**
     * Will test that the global search respects individual column settings
     */
    public function testGlobalSearchWithIndividualColumn()
    {
        $data = [
            ['id' => 1, 'name' => 'foo'],
            ['id' => 2, 'name' => 'bar'],
        ];

        $queryConfiguration = QueryConfigurationBuilder::create()
            ->start(0)
            ->length(2)
            ->searchValue('foo')
            ->drawCall(1)
            ->build();

        $columnConfiguration = ColumnConfigurationBuilder::create()
            ->name('id')
            ->build();

        $columnConfiguration2 = ColumnConfigurationBuilder::create()
            ->name('name')
            ->searchable(Searchable::NONE())
            ->build();

        $provider = new CollectionProvider(new Collection($data));

        $provider->prepareForProcessing($queryConfiguration, [$columnConfiguration, $columnConfiguration2]);
        $data = $provider->process();

        $this->assertSame(0, $data->data()->count());
    }

    public function testColumnSearch()
    {
        $data = [
            ['id' => 1, 'name' => 'foo'],
            ['id' => 2, 'name' => 'bar'],
        ];

        $queryConfiguration = QueryConfigurationBuilder::create()
            ->start(0)
            ->length(2)
            ->columnSearch('name', 'foo')
            ->drawCall(1)
            ->build();

        $columnConfiguration = ColumnConfigurationBuilder::create()
            ->name('name')
            ->build();

        $provider = new CollectionProvider(new Collection($data));

        $provider->prepareForProcessing($queryConfiguration, [$columnConfiguration]);
        $data = $provider->process();

        $this->assertSame(1, $data->data()->count());
    }

    public function testColumnSearch2()
    {
        $data = [
            ['id' => 1, 'name' => 'foo'],
            ['id' => 2, 'name' => 'bar'],
        ];

        $queryConfiguration = QueryConfigurationBuilder::create()
            ->start(0)
            ->length(2)
            ->columnSearch('id', '2')
            ->drawCall(1)
            ->build();

        $columnConfiguration = ColumnConfigurationBuilder::create()
            ->name('id')
            ->build();

        $provider = new CollectionProvider(new Collection($data));

        $provider->prepareForProcessing($queryConfiguration, [$columnConfiguration]);
        $data = $provider->process();

        $this->assertSame(1, $data->data()->count());
    }

    public function testCustomColumn()
    {
        $data = [
            ['id' => 1, 'name' => 'foo'],
            ['id' => 2, 'name' => 'bar'],
        ];

        $queryConfiguration = QueryConfigurationBuilder::create()
            ->start(0)
            ->length(2)
            ->columnSearch('id', 'foo')
            ->drawCall(1)
            ->build();

        $columnConfiguration = ColumnConfigurationBuilder::create()
            ->name('id')
            ->build();

        $provider = new CollectionProvider(new Collection($data));
        $provider->searchColumn('id', function ($data, $search) {
            // we only accept columns with the id 1 if the user searched in the column
            return $data['id'] == 1;
        });

        $provider->prepareForProcessing($queryConfiguration, [$columnConfiguration]);
        $data = $provider->process();

        $this->assertSame(1, $data->data()->count());
    }

    public function testCustomOrder()
    {
        $data = [
            ['id' => 1, 'name' => 'foo'],
            ['id' => 2, 'name' => 'bar'],
        ];

        $queryConfiguration = QueryConfigurationBuilder::create()
            ->start(0)
            ->length(2)
            ->drawCall(1)
            ->columnOrder('id', 'desc')
            ->build();

        $columnConfiguration = ColumnConfigurationBuilder::create()
            ->name('id')
            ->build();

        $provider = new CollectionProvider(new Collection($data));

        $provider->order(/**
         * @param $first
         * @param $second
         * @param ColumnOrder[] $order
         * @return int
         */
        function($first, $second, array $order){
            if(!$order[0]->isAscending()) {
                return ($first['id'] < $second['id']) ? 1 : (($first['id'] > $second['id']) ? -1 : 0);
            }
            return ($first['id'] < $second['id']) ? -1 : (($first['id'] > $second['id']) ? 1 : 0);
        });

        $provider->prepareForProcessing($queryConfiguration, [$columnConfiguration]);
        $data = $provider->process();

        $this->assertSame(2, $data->data()->count());
        $first = $data->data()->first();

        $this->assertSame(2, $first['id']);
    }

    public function testDefaultOrder()
    {
        $data = [
            ['id' => 1, 'name' => 'foo'],
            ['id' => 2, 'name' => 'bar'],
        ];

        $queryConfiguration = QueryConfigurationBuilder::create()
            ->start(0)
            ->length(2)
            ->drawCall(1)
            ->columnOrder('id', 'desc')
            ->build();

        $columnConfiguration = ColumnConfigurationBuilder::create()
            ->name('id')
            ->build();

        $provider = new CollectionProvider(new Collection($data));

        $provider->prepareForProcessing($queryConfiguration, [$columnConfiguration]);
        $data = $provider->process();

        $this->assertSame(2, $data->data()->count());
        $first = $data->data()->first();

        $this->assertSame(2, $first['id']);
    }

    public function testDefaultOrder2()
    {
        $data = [
            ['id' => 1, 'name' => 'foo'],
            ['id' => 2, 'name' => 'bar'],
        ];

        $queryConfiguration = QueryConfigurationBuilder::create()
            ->start(0)
            ->length(2)
            ->drawCall(1)
            ->columnOrder('id', 'asc')
            ->build();

        $columnConfiguration = ColumnConfigurationBuilder::create()
            ->name('id')
            ->build();

        $provider = new CollectionProvider(new Collection($data));

        $provider->prepareForProcessing($queryConfiguration, [$columnConfiguration]);
        $data = $provider->process();

        $this->assertSame(2, $data->data()->count());
        $first = $data->data()->first();

        $this->assertSame(1, $first['id']);
    }

    public function testDefaultOrderMulti()
    {
        $data = [
            ['id' => 1, 'name' => 'foo'],
            ['id' => 2, 'name' => 'bar'],
            ['id' => 3, 'name' => 'foo'],
            ['id' => 4, 'name' => 'bar'],
        ];

        $queryConfiguration = QueryConfigurationBuilder::create()
            ->start(0)
            ->length(4)
            ->drawCall(1)
            ->columnOrder('name', 'asc')
            ->columnOrder('id', 'desc')
            ->build();

        $columnConfiguration = ColumnConfigurationBuilder::create()
            ->name('id')
            ->build();

        $columnConfiguration2 = ColumnConfigurationBuilder::create()
            ->name('name')
            ->build();

        $provider = new CollectionProvider(new Collection($data));

        $provider->prepareForProcessing($queryConfiguration, [$columnConfiguration, $columnConfiguration2]);
        $data = $provider->process();

        $this->assertSame(4, $data->data()->count());
        $first = $data->data()->first();
        $second = $data->data()->get(1);

        $this->assertSame(4, $first['id']);
        $this->assertSame(2, $second['id']);
    }
}
