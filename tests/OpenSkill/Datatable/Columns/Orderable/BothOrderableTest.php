<?php

namespace packages\OpenSkill\Datatable\tests\OpenSkill\Datatable\Columns\Orderable;

use OpenSkill\Datatable\Columns\Orderable\BothOrderable;

class BothOrderableTest extends \PHPUnit_Framework_TestCase
{

    public function testClass()
    {
        $t = new BothOrderable();
        $this->assertTrue($t->isOrderable());
    }
}
