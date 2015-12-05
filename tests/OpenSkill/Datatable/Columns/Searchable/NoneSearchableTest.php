<?php

namespace packages\OpenSkill\Datatable\tests\OpenSkill\Datatable\Columns\Searchable;

use OpenSkill\Datatable\Columns\Searchable\Searchable;

class NoneSearchableTest extends \PHPUnit_Framework_TestCase
{
    public function testClass()
    {
        $t = Searchable::NONE();
        $this->assertFalse($t->isSearchable());
    }
}
