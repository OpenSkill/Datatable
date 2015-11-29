<?php

use OpenSkill\Datatable\Columns\ColumnConfiguration;
use OpenSkill\Datatable\Columns\ColumnConfigurationBuilder;
use OpenSkill\Datatable\Columns\Orderable\Orderable;
use OpenSkill\Datatable\Columns\Searchable\Searchable;
use OpenSkill\Datatable\Composers\ColumnComposer;
use OpenSkill\Datatable\Providers\Provider;
use OpenSkill\Datatable\Versions\Version;
use OpenSkill\Datatable\Versions\VersionEngine;

class DTDataComposerTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var ColumnComposer
     */
    protected $composer;

    /**
     * @var Provider
     */
    protected $provider;

    /**
     * @var Version
     */
    private $version;

    /**
     * @var VersionEngine
     */
    private $versionEngine;

    /**
     * Will set up a mocked provider and the class to test
     */
    protected function setUp()
    {
        $this->provider = Mockery::mock('OpenSkill\Datatable\Providers\Provider');
        $this->version = Mockery::mock('OpenSkill\Datatable\Versions\Version');
        $this->versionEngine = Mockery::mock('OpenSkill\Datatable\Versions\VersionEngine');
        $this->composer = new ColumnComposer($this->provider, $this->versionEngine);
    }

    /**
     * Close the mock engine
     */
    public function tearDown()
    {
        Mockery::close();
    }

    /**
     * Test for setting and getting the provider
     */
    public function testGetProvider()
    {
        $this->assertTrue($this->provider != null);
        $this->assertTrue($this->composer != null);
        $this->assertSame($this->provider, $this->composer->getProvider());
    }

    /**
     * Will check if the column method with just the name has all values set to the right defaults
     */
    public function testNameColumn()
    {
        $name = "fooBar";

        $this->composer->column($name);

        // get configuration and verify
        $numberOfColumns = count($this->composer->getColumnConfiguration());
        $this->assertSame($numberOfColumns, 1, "There should only be one column configuration");

        /**
         * @var ColumnConfiguration
         */
        $cc = $this->composer->getColumnConfiguration()[0];

        $this->assertTrue($cc->getOrder()->isOrderable(), "The column should be orderable");
        $this->assertTrue($cc->getSearch()->isSearchable(), "The column should be searchable");
        $this->assertSame($name, $cc->getName(), "The name should be set to 'fooBar'");
    }

    /**
     * Will check if the column method with the name and a callable has all values set to the right defaults
     */
    public function testNameFunctionColumn()
    {
        $name = "fooBar";
        $callable = function ($data) {
            return "bar";
        };

        $this->composer->column($name, $callable);

        // get configuration and verify
        $numberOfColumns = count($this->composer->getColumnConfiguration());
        $this->assertSame($numberOfColumns, 1, "There should only be one column configuration");

        /**
         * @var ColumnConfiguration
         */
        $cc = $this->composer->getColumnConfiguration()[0];

        /**
         * @var callable
         */
        $func = $cc->getCallable();

        $this->assertTrue($cc->getOrder()->isOrderable(), "The column should be orderable");
        $this->assertTrue($cc->getSearch()->isSearchable(), "The column should be searchable");
        $this->assertSame($name, $cc->getName(), "The name should be set to 'fooBar'");
        $this->assertSame("bar", $func("fooBar"));
    }

    /**
     * Will check if the column method with the name and a callable as well as the searchable flag has all values set
     * to the right defaults
     */
    public function testNameFunctionSearchableColumn()
    {
        $name = "fooBar";
        $callable = function ($data) {
            return "bar";
        };

        $this->composer->column($name, $callable, Searchable::NONE());

        // get configuration and verify
        $numberOfColumns = count($this->composer->getColumnConfiguration());
        $this->assertSame($numberOfColumns, 1, "There should only be one column configuration");

        /**
         * @var ColumnConfiguration
         */
        $cc = $this->composer->getColumnConfiguration()[0];

        /**
         * @var callable
         */
        $func = $cc->getCallable();

        $this->assertFalse($cc->getSearch()->isSearchable(), "The column should not be searchable");
        $this->assertTrue($cc->getOrder()->isOrderable(), "The column should be orderable");
        $this->assertSame($name, $cc->getName(), "The name should be set to 'fooBar'");
        $this->assertSame("bar", $func("fooBar"));
    }

    /**
     * Will check if the column method with the name and a callable as well as the searchable and orderable flag has
     * all values set to the right defaults
     */
    public function testNameFunctionSearchableOrderableColumn()
    {
        $name = "fooBar";
        $callable = function ($data) {
            return "bar";
        };

        $this->composer->column($name, $callable, Searchable::NONE(), Orderable::NONE());

        // get configuration and verify
        $numberOfColumns = count($this->composer->getColumnConfiguration());
        $this->assertSame($numberOfColumns, 1, "There should only be one column configuration");

        /**
         * @var ColumnConfiguration
         */
        $cc = $this->composer->getColumnConfiguration()[0];

        /**
         * @var callable
         */
        $func = $cc->getCallable();

        $this->assertFalse($cc->getSearch()->isSearchable(), "The column should not be searchable");
        $this->assertFalse($cc->getOrder()->isOrderable(), "The column should be orderable");
        $this->assertSame($name, $cc->getName(), "The name should be set to 'fooBar'");
        $this->assertSame("bar", $func("fooBar"));
    }

    /**
     * Will test that the column method will throw exceptions on invalid name
     *
     * @expectedException InvalidArgumentException
     */
    public function testNameExceptions()
    {
        $this->composer->column(false);
    }

    /**
     * Will test if the composer functions correct when the configure method is called
     */
    public function testConfigureColumn()
    {
        $name = "fooBar";
        $config = ColumnConfigurationBuilder::create()
            ->name($name)
            ->build();

        $this->composer->add($config);

        // get configuration and verify
        $numberOfColumns = count($this->composer->getColumnConfiguration());
        $this->assertSame($numberOfColumns, 1, "There should only be one column configuration");

        /**
         * @var ColumnConfiguration
         */
        $cc = $this->composer->getColumnConfiguration()[0];

        $func = $cc->getCallable();

        $this->assertFalse($cc->getSearch()->isSearchable(), "The column should not be searchable");
        $this->assertFalse($cc->getOrder()->isOrderable(), "The column should be orderable");
        $this->assertSame($name, $cc->getName(), "The name should be set to 'fooBar'");
        $this->assertSame("bar", $func(["fooBar" => "bar"]));
    }
}
