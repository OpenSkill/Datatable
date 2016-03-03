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
class ReflectionQueryBuilderProviderTest extends \PHPUnit_Framework_TestCase
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

    public function testNoExceptionIfColumnIsFound()
    {
        $queryConfiguration = QueryConfigurationBuilder::create()
            ->start(0)
            ->length(2)
            ->drawCall(1)
            ->columnOrder('name', 'desc')
            ->build();

        $columnConfiguration = ColumnConfigurationBuilder::create()
            ->name('foundColumn')
            ->build();

        /**
         * Create a mocked query builder...
         */
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

        $getColumnFromName = new \ReflectionMethod($provider, 'getColumnFromName');
        $getColumnFromName->setAccessible(true);

        //$provider->getColumnFromName('notFound');
        $getColumnFromName->invoke($provider, 'foundColumn');
    }

    /**
     * @expectedException OpenSkill\Datatable\DatatableException
     * @expectedExceptionMessage A requested column was not found in the columnConfiguration.
     */
    public function testExceptionIfColumnIsNotFound()
    {
        $queryConfiguration = QueryConfigurationBuilder::create()
            ->start(0)
            ->length(2)
            ->drawCall(1)
            ->columnOrder('name', 'desc')
            ->build();

        $columnConfiguration = ColumnConfigurationBuilder::create()
            ->name('foundColumn')
            ->build();

        /**
         * Create a mocked query builder...
         */
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

        $getColumnFromName = new \ReflectionMethod($provider, 'getColumnFromName');
        $getColumnFromName->setAccessible(true);

        //$provider->getColumnFromName('notFound');
        $getColumnFromName->invoke($provider, 'notFound');
    }

    public function tearDown()
    {
        \Mockery::close();
    }
}
