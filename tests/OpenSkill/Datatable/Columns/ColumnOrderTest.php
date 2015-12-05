<?php

namespace OpenSkill\Datatable\Columns;


/**
 * Class ColumnOrderTest
 * @package OpenSkill\Datatable\Columns
 *
 * We test *every* class even it does not make sense to test a value object
 */
class ColumnOrderTest extends \PHPUnit_Framework_TestCase
{

    public function testClazz()
    {
        $t = new ColumnOrder('fooBar', true);
        $this->assertSame('fooBar', $t->columnName());
        $this->assertTrue($t->isAscending());
    }
}
