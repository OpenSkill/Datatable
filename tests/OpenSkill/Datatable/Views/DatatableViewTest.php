<?php

namespace OpenSkill\Datatable\Views;


use Mockery\Matcher\Closure;
use Mockery\Mock;
use OpenSkill\Datatable\Columns\ColumnConfiguration;
use OpenSkill\Datatable\Columns\ColumnConfigurationBuilder;

class DatatableViewTest extends \PHPUnit_Framework_TestCase
{

    /** @var Mock */
    private $viewFactory;

    /** @var Mock */
    private $configRepository;

    /** @var DatatableView */
    private $dtv;

    /** @var DatatableView */
    private $dtv2;

    /** @var ColumnConfiguration */
    private $column;

    /** @var Mock */
    private $view;

    public function setUp()
    {
        $this->viewFactory = \Mockery::mock('Illuminate\Contracts\View\Factory');
        $this->configRepository = \Mockery::mock('Illuminate\Contracts\Config\Repository');
        $this->configRepository->shouldReceive('get')->andReturn("fooBar");
        $this->configRepository->shouldReceive('render')->andReturn("fooBar");
        $this->view = \Mockery::mock('Illuminate\Contracts\View\View');
        $this->view->shouldReceive('render')->andReturn('fooBar');

        $this->dtv = new DatatableView(
            "fooTable",
            "fooScript",
            $this->viewFactory,
            $this->configRepository,
            []
        );

        $this->column = ColumnConfigurationBuilder::create()
            ->name('id')
            ->build();

        $this->dtv2 = new DatatableView(
            "fooTable",
            "fooScript",
            $this->viewFactory,
            $this->configRepository,
            [$this->column]
        );
    }


    public function testConstructWithColumns()
    {
        $this->viewFactory = \Mockery::mock('Illuminate\Contracts\View\Factory');
        $this->configRepository = \Mockery::mock('Illuminate\Contracts\Config\Repository');
        $this->configRepository->shouldReceive('get')->andReturn("fooBar");

        $column = ColumnConfigurationBuilder::create()
            ->name('id')
            ->build();

        $this->dtv = new DatatableView(
            "fooTable",
            "fooScript",
            $this->viewFactory,
            $this->configRepository,
            [$column]
        );
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidId()
    {
        $this->dtv->id(123);
    }

    public function testId()
    {
        $this->viewFactory->shouldReceive('make')
            ->withArgs([
                'fooTable',
                [
                    'columns' => ['id' => 'id'],
                    'showHeaders' => false,
                    'id' => 123,
                    'endpoint' => '/'
                ]
            ])
            ->times(1)
            ->andReturn($this->view);

        $this->dtv2->id("123");
        $this->dtv2->table();

    }

    public function testOption()
    {
        $this->viewFactory->shouldReceive('make')
            ->withArgs([
                'fooScript',
                [
                    'id' => 'fooBar',
                    'columns' => ['id' => 'id'],
                    'options' => [
                        'fooBar' => 'fooBar',
                        'fooBar1' => ['fooBar']
                    ],
                    'callbacks' => [],
                    'endpoint' => '/'
                ]
            ])
            ->times(1)
            ->andReturn($this->view);

        $this->dtv2->option('fooBar', 'fooBar');
        $this->dtv2->option('fooBar1', ['fooBar']);

        $this->dtv2->script();
    }

    public function testCallback()
    {
        $this->viewFactory->shouldReceive('make')
            ->withArgs([
                'fooScript',
                [
                    'id' => 'fooBar',
                    'columns' => ['id' => 'id'],
                    'options' => [],
                    'callbacks' => [
                        'fooBar' => 'fooBar',
                        'fooBar1' => ['fooBar']
                    ],
                    'endpoint' => '/'
                ]
            ])
            ->times(1)
            ->andReturn($this->view);

        $this->dtv2->callback('fooBar', 'fooBar');
        $this->dtv2->callback('fooBar1', ['fooBar']);

        $this->dtv2->script();
    }

    public function testHeaders()
    {
        $this->viewFactory->shouldReceive('make')
            ->withArgs([
                'fooTable',
                [
                    'columns' => ['id' => 'id'],
                    'showHeaders' => true,
                    'id' => 'fooBar',
                    'endpoint' => '/'
                ],
            ])
            ->times(1)
            ->andReturn($this->view);

        $this->dtv2->headers();

        $this->dtv2->table();
    }

    public function testColumns()
    {
        $this->viewFactory->shouldReceive('make')
            ->withArgs([
                'fooTable',
                [
                    'columns' => [
                        'fooBar' => 'fooBarLabel',
                        'fooBar2' => 'fooBar2'
                    ],
                    'showHeaders' => false,
                    'id' => 'fooBar',
                    'endpoint' => '/'
                ],
            ])
            ->times(1)
            ->andReturn($this->view);

        $this->viewFactory->shouldReceive('make')
            ->withArgs([
                'fooScript',
                [
                    'id' => 'fooBar',
                    'columns' => [
                        'fooBar' => 'fooBarLabel',
                        'fooBar2' => 'fooBar2'
                    ],
                    'options' => [],
                    'callbacks' => [],
                    'endpoint' => '/'
                ]
            ])
            ->times(1)
            ->andReturn($this->view);

        $this->dtv2->columns('fooBar', 'fooBarLabel');
        $this->dtv2->columns('fooBar2');

        $this->dtv2->html();
    }

    public function testEndpoint()
    {
        $this->viewFactory->shouldReceive('make')
            ->withArgs([
                'fooTable',
                [
                    'columns' => ['id' => 'id'],
                    'showHeaders' => false,
                    'id' => 'fooBar',
                    'endpoint' => '/test/endpoint/gets/set'
                ],
            ])
            ->times(1)
            ->andReturn($this->view);

        $this->viewFactory->shouldReceive('make')
            ->withArgs([
                'fooScript',
                [
                    'id' => 'fooBar',
                    'columns' => ['id' => 'id'],
                    'options' => [],
                    'callbacks' => [],
                    'endpoint' => '/test/endpoint/gets/set'
                ]
            ])
            ->times(1)
            ->andReturn($this->view);


        $this->dtv2->endpoint('/test/endpoint/gets/set');
        $this->dtv2->html();
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidColumns()
    {
        $this->dtv->columns(123);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidColumsTable()
    {
        $this->dtv->table();
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidColumsScript()
    {
        $this->dtv->script();
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidColumsHtml()
    {
        $this->dtv->html();
    }

    public function tearDown()
    {
        \Mockery::close();
    }


}
