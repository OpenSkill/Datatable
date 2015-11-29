<?php

use OpenSkill\Datatable\Columns\ColumnConfigurationBuilder;
use OpenSkill\Datatable\Columns\Orderable\Orderable;
use OpenSkill\Datatable\Columns\Searchable\Searchable;

class ColumnConfigurationTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Simple test that will test if the immutable object is created as wished
     */
    public function testBasicColumnConfiguration()
    {
        $name = "fooBar";

        $cc = ColumnConfigurationBuilder::create()
            ->name($name)
            ->searchable(Searchable::REGEX())
            ->orderable(Orderable::NONE())
            ->build();

        $this->assertSame($name, $cc->getName(), "Name should be set correctly");
        $this->assertTrue($cc->getSearch()->isSearchable(), "The column should be searchable");
        $this->assertFalse($cc->getOrder()->isOrderable(), "The column should be orderable");
    }

    /**
     * Will test if the builder will throw an exception on an empty name
     * @expectedException InvalidArgumentException
     */
    public function testInvalidConfiguration()
    {
        ColumnConfigurationBuilder::create()
            ->name("")
            ->build();
    }

    /**
     * Will test if the default callable can work with multiple data representations
     */
    public function testCallable()
    {
        $obj = new FooClass();

        $cc = ColumnConfigurationBuilder::create()
            ->name("fooBar")
            ->build();

        $func = $cc->getCallable();

        $this->assertSame("", $func(["foo" => "bar"]));

        $this->assertSame("bar", $func(["fooBar" => "bar"]));

        $this->assertTrue(is_object($obj));

        $this->assertSame("", $func($obj));

        $cc = ColumnConfigurationBuilder::create()
            ->name("fooProperty")
            ->build();

        $func = $cc->getCallable();

        $this->assertSame("barProperty", $func($obj));

        $cc = ColumnConfigurationBuilder::create()
            ->name("fooMethod")
            ->build();

        $func = $cc->getCallable();

        $this->assertSame("barMethod", $func($obj));
    }
}

class FooClass
{
    /**
     * FooClass constructor.
     */
    public function __construct()
    {
    }

    public $fooProperty = "barProperty";

    public function fooMethod()
    {
        return "barMethod";
    }
}
