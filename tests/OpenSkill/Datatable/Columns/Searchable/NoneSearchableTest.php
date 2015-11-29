<?php

namespace packages\OpenSkill\Datatable\tests\OpenSkill\Datatable\Columns\Searchable;


use OpenSkill\Datatable\Columns\Searchable\NoneSearchable;

class NoneSearchableTest extends \PHPUnit_Framework_TestCase
{
    public function testClass()
    {
        $t = new NoneSearchable();
        $this->assertFalse($t->isSearchable());
    }
}
