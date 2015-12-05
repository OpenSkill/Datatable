<?php

namespace packages\OpenSkill\Datatable\tests\OpenSkill\Datatable\Columns\Orderable;

use OpenSkill\Datatable\Columns\Orderable\Orderable;

class DescOrderableTest extends \PHPUnit_Framework_TestCase
{

    public function testClass()
    {
        $t = Orderable::DESC();
        $this->assertTrue($t->isOrderable());
    }
}
