<?php

namespace packages\OpenSkill\Datatable\tests\OpenSkill\Datatable\Columns\Orderable;

use OpenSkill\Datatable\Columns\Orderable\Orderable;

class AscOrderableTest extends \PHPUnit_Framework_TestCase
{

    public function testClass()
    {
        $t = Orderable::ASC();
        $this->assertTrue($t->isOrderable());
    }
}
