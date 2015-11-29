<?php

namespace packages\OpenSkill\Datatable\tests\OpenSkill\Datatable\Columns\Orderable;

use OpenSkill\Datatable\Columns\Orderable\AscOrderable;

class AscOrderableTest extends \PHPUnit_Framework_TestCase
{

    public function testClass()
    {
        $t = new AscOrderable();
        $this->assertTrue($t->isOrderable());
    }
}
