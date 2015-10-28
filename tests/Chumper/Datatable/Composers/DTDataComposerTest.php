<?php

use Chumper\Datatable\Columns\ColumnConfiguration;
use Chumper\Datatable\Composers\DTDataComposer;
use Chumper\Datatable\Providers\DTProvider;

class DTDataComposerTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var DTDataComposer
     */
    protected $composer;

    /**
     * @var DTProvider
     */
    protected $provider;

    /**
     * Will set up a mocked provider and the class to test
     */
    protected function setUp()
    {
        $this->provider = Mockery::mock('Chumper\Datatable\Providers\DTProvider');
        $this->composer = new DTDataComposer($this->provider);
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

        $this->assertTrue($cc->isOrderable(), "The column should be orderable");
        $this->assertTrue($cc->isSearchable(), "The column should be searchable");
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

        $this->assertTrue($cc->isOrderable(), "The column should be orderable");
        $this->assertTrue($cc->isSearchable(), "The column should be searchable");
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

        $this->composer->column($name, $callable, false);

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

        $this->assertFalse($cc->isSearchable(), "The column should not be searchable");
        $this->assertTrue($cc->isOrderable(), "The column should be orderable");
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

        $this->composer->column($name, $callable, false, false);

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

        $this->assertFalse($cc->isSearchable(), "The column should not be searchable");
        $this->assertFalse($cc->isOrderable(), "The column should be orderable");
        $this->assertSame($name, $cc->getName(), "The name should be set to 'fooBar'");
        $this->assertSame("bar", $func("fooBar"));
    }

    /**
     * Will test that the column method will throw exceptions on invalid name
     *
     * @expectedException InvalidArgumentException
     */
    public function testNameExceptions() {
        $this->composer->column(false);
    }

    /**
     * Will test that the column method will throw exceptions on invalid searchable flag
     *
     * @expectedException InvalidArgumentException
     */
    public function testSearchableExceptions() {
        $this->composer->column("fooBar", function($data) { return "bar"; }, "false");
    }

    /**
     * Will test that the column method will throw exceptions on invalid orderable flag
     *
     * @expectedException InvalidArgumentException
     */
    public function testOrderableExceptions() {
        $this->composer->column("fooBar", function($data) { return "bar"; }, false, "false");
    }
}
