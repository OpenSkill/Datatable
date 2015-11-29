<?php

namespace packages\OpenSkill\Datatable\tests\OpenSkill\Datatable\Columns\Orderable;


use OpenSkill\Datatable\Columns\Orderable\DescOrderable;

class DescOrderableTest extends \PHPUnit_Framework_TestCase
{

    public function testClass()
    {
        $t = new DescOrderable();
        $this->assertTrue($t->isOrderable());
    }
}
