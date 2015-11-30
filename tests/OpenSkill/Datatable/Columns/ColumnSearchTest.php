<?php

namespace OpenSkill\Datatable\Columns;


/**
 * Class ColumnSearchTest
 * @package OpenSkill\Datatable\Columns
 *
 * We test *every* class even if it makes no sense to test a value object
 */
class ColumnSearchTest extends \PHPUnit_Framework_TestCase
{

    public function testClazz()
    {
        $d = new ColumnSearch('fooBar', 'fooSearch');

        $this->assertSame('fooBar', $d->columnName());
        $this->assertSame('fooSearch', $d->searchValue());
    }
}
