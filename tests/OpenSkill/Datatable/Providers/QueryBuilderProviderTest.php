<?php

namespace packages\OpenSkill\Datatable\tests\OpenSkill\Datatable\Providers;

use Illuminate\Support\Collection;
use OpenSkill\Datatable\Columns\ColumnConfigurationBuilder;
use OpenSkill\Datatable\Columns\ColumnOrder;
use OpenSkill\Datatable\Columns\Searchable\Searchable;
use OpenSkill\Datatable\Providers\QueryBuilderProvider;
use OpenSkill\Datatable\Queries\QueryConfigurationBuilder;

/**
 * Class QueryBuilderProviderTest
 * @package packages\OpenSkill\Datatable\tests\OpenSkill\Datatable\Providers
 */
class QueryBuilderProviderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Illuminate\Database\Query\Builder
     */
    private $mockedQB;

    private function setupMockQueryBuilder()
    {
        $this->mockedQB = \Mockery::mock('Illuminate\Database\Query\Builder');
        return $this->mockedQB;
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testProcessWithNoSetup()
    {
        $queryBuilder = $this->setupMockQueryBuilder();

        $provider = new QueryBuilderProvider($queryBuilder);
        $provider->process();
    }

    public function testProcess()
    {
        $queryConfiguration = QueryConfigurationBuilder::create()
            ->start(0)
            ->length(2)
            ->drawCall(1)
            ->columnOrder('name', 'desc')
            ->build();

        $columnConfiguration = ColumnConfigurationBuilder::create()
            ->name('name')
            ->build();

        // Set up mock item
        $queryBuilder = $this->setupMockQueryBuilder();
        $queryBuilder
            ->shouldReceive('orderBy')
            ->with('name', 'desc')
            ->once();

        $queryBuilder
            ->shouldReceive('skip')
            ->with(0);

        $queryBuilder
            ->shouldReceive('limit')
            ->with(2);

        $queryBuilder
            ->shouldReceive('count')
            ->withNoArgs();

        $queryBuilder
            ->shouldReceive('get')
            ->withArgs([['name']]);

        $provider = new QueryBuilderProvider($queryBuilder);
        $provider->prepareForProcessing($queryConfiguration, [$columnConfiguration]);
        $provider->process();
    }

    /**
     * Will test if a global custom search will return all results
     */
    public function testGlobalSearch()
    {
        $queryConfiguration = QueryConfigurationBuilder::create()
            ->start(0)
            ->length(2)
            ->drawCall(1)
            ->searchValue("foo2")
            ->build();

        $columnConfiguration = ColumnConfigurationBuilder::create()
            ->name('name')
            ->build();

        // Set up mock item
        $queryBuilder = $this->setupMockQueryBuilder();
        $queryBuilder
            ->shouldReceive('orderBy')
            ->with('name', 'desc')
            ->once();

        $queryBuilder
            ->shouldReceive('skip')
            ->with(0);

        $queryBuilder
            ->shouldReceive('limit')
            ->with(2);

        $queryBuilder
            ->shouldReceive('count')
            ->withNoArgs();

        $queryBuilder
            ->shouldReceive('orWhere')
            ->withArgs(["name", "LIKE", "%foo2%"]);

        $queryBuilder
            ->shouldReceive('get')
            ->withArgs([['name']]);

        $provider = new QueryBuilderProvider($queryBuilder);

        $provider->prepareForProcessing($queryConfiguration, [$columnConfiguration]);
        $provider->process();
    }

    /**
     * Will test that the global search respects individual column settings
     */
    public function testGlobalSearchWithIndividualColumn()
    {
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

        // Set up mock item
        $queryBuilder = $this->setupMockQueryBuilder();
        $queryBuilder
            ->shouldReceive('orderBy')
            ->with('name', 'desc')
            ->once();

        $queryBuilder
            ->shouldReceive('skip')
            ->with(0);

        $queryBuilder
            ->shouldReceive('limit')
            ->with(2);

        $queryBuilder
            ->shouldReceive('count')
            ->withNoArgs();

        $queryBuilder
            ->shouldReceive('orWhere')
            ->withArgs(["id", "LIKE", "%foo%"]);

        $queryBuilder
            ->shouldReceive('get')
            ->withArgs([['id', 'name']]);

        $provider = new QueryBuilderProvider($queryBuilder);
        $provider->prepareForProcessing($queryConfiguration, [$columnConfiguration, $columnConfiguration2]);
        $provider->process();
    }

    public function testColumnSearch2()
    {
        $queryConfiguration = QueryConfigurationBuilder::create()
            ->start(0)
            ->length(2)
            ->columnSearch('id', '2')
            ->drawCall(1)
            ->build();

        $columnConfiguration = ColumnConfigurationBuilder::create()
            ->name('id')
            ->build();

        // Set up mock item
        $queryBuilder = $this->setupMockQueryBuilder();
        $queryBuilder
            ->shouldReceive('orderBy')
            ->with('name', 'desc')
            ->once();

        $queryBuilder
            ->shouldReceive('skip')
            ->with(0);

        $queryBuilder
            ->shouldReceive('limit')
            ->with(2);

        $queryBuilder
            ->shouldReceive('count')
            ->withNoArgs();

        $queryBuilder
            ->shouldReceive('orWhere')
            ->withArgs(["id", "LIKE", "%2%"]);

        $queryBuilder
            ->shouldReceive('get')
            ->withArgs([['id']]);

        $provider = new QueryBuilderProvider($queryBuilder);
        $provider->prepareForProcessing($queryConfiguration, [$columnConfiguration]);
        $provider->process();
    }

    public function testNoOrder()
    {
        $queryConfiguration = QueryConfigurationBuilder::create()
            ->start(0)
            ->length(2)
            ->drawCall(1)
            ->columnOrder('name', 'asc')
            ->build();

        $columnConfiguration = ColumnConfigurationBuilder::create()
            ->name('name')
            ->build();

        // Set up mock item
        $queryBuilder = $this->setupMockQueryBuilder();
        $queryBuilder
            ->shouldReceive('orderBy')
            ->with('name', 'desc')
            ->once();

        $queryBuilder
            ->shouldReceive('skip')
            ->with(0);

        $queryBuilder
            ->shouldReceive('limit')
            ->with(2);

        $queryBuilder
            ->shouldReceive('count')
            ->withNoArgs();

        $queryBuilder
            ->shouldReceive('orWhere')
            ->withArgs(["id", "LIKE", "%2%"]);

        $queryBuilder
            ->shouldReceive('orderBy')
            ->withArgs(["name", "asc"]);

        $queryBuilder
            ->shouldReceive('get')
            ->withArgs([['name']]);

        $provider = new QueryBuilderProvider($queryBuilder);
        $provider->prepareForProcessing($queryConfiguration, [$columnConfiguration]);
        $provider->process();
    }

    public function testDefaultOrderMulti()
    {
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

        // Set up mock item
        $queryBuilder = $this->setupMockQueryBuilder();
        $queryBuilder
            ->shouldReceive('orderBy')
            ->with('name', 'desc')
            ->once();

        $queryBuilder
            ->shouldReceive('skip')
            ->with(0);

        $queryBuilder
            ->shouldReceive('limit')
            ->with(4);

        $queryBuilder
            ->shouldReceive('count')
            ->withNoArgs();

        $queryBuilder
            ->shouldReceive('orWhere')
            ->withArgs(["id", "LIKE", "%2%"]);


        $queryBuilder
            ->shouldReceive('orderBy')
            ->withArgs(["id", "desc"])
            ->once();

        $queryBuilder
            ->shouldReceive('orderBy')
            ->withArgs(["name", "asc"])
            ->once();

        $queryBuilder
            ->shouldReceive('get')
            ->withArgs([['id', 'name']]);

        $provider = new QueryBuilderProvider($queryBuilder);
        $provider->prepareForProcessing($queryConfiguration, [$columnConfiguration, $columnConfiguration2]);
        $provider->process();
    }
}
