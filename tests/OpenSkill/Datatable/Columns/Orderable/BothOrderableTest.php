<?php

namespace packages\OpenSkill\Datatable\tests\OpenSkill\Datatable\Columns\Orderable;

use OpenSkill\Datatable\Columns\Orderable\Orderable;

class BothOrderableTest extends \PHPUnit_Framework_TestCase
{

    public function testClass()
    {
        $t = Orderable::BOTH();
        $this->assertTrue($t->isOrderable());
    }
}
