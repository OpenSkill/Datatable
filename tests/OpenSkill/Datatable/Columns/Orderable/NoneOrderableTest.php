<?php

namespace packages\OpenSkill\Datatable\tests\OpenSkill\Datatable\Columns\Orderable;

use OpenSkill\Datatable\Columns\Orderable\NoneOrderable;

class NoneOrderableTest extends \PHPUnit_Framework_TestCase
{
    public function testClass()
    {
        $t = new NoneOrderable();
        $this->assertFalse($t->isOrderable());
    }
}
