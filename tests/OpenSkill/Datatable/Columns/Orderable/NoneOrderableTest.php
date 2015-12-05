<?php

namespace packages\OpenSkill\Datatable\tests\OpenSkill\Datatable\Columns\Orderable;

use OpenSkill\Datatable\Columns\Orderable\Orderable;

class NoneOrderableTest extends \PHPUnit_Framework_TestCase
{
    public function testClass()
    {
        $t = Orderable::NONE();
        $this->assertFalse($t->isOrderable());
    }
}
