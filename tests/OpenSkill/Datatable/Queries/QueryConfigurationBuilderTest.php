<?php

namespace packages\OpenSkill\Datatable\tests\OpenSkill\Datatable\Queries;

use OpenSkill\Datatable\Queries\QueryConfigurationBuilder;

class QueryConfigurationBuilderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testDrawException()
    {
        $b = QueryConfigurationBuilder::create();
        $b->drawCall(false);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testStartException()
    {
        $b = QueryConfigurationBuilder::create();
        $b->start('asd');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testLengthException()
    {
        $b = QueryConfigurationBuilder::create();
        $b->length('asd');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSearchException()
    {
        $b = QueryConfigurationBuilder::create();
        $b->searchValue(123);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRegexException()
    {
        $b = QueryConfigurationBuilder::create();
        $b->searchRegex(123);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testOrderException()
    {
        $b = QueryConfigurationBuilder::create();
        $b->columnOrder('foo', 123);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSearchOrderException()
    {
        $b = QueryConfigurationBuilder::create();
        $b->columnSearch("foo", 123);
    }
}
