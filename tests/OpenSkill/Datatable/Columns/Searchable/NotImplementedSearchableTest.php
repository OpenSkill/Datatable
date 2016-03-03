<?php

namespace packages\OpenSkill\Datatable\tests\OpenSkill\Datatable\Columns\Searchable;

use OpenSkill\Datatable\Columns\Searchable\Searchable;

class NotImplementedSearchableTest extends \PHPUnit_Framework_TestCase
{
    public function testClass()
    {
        $t = Searchable::NOTIMPLEMENTED();
        $this->assertTrue($t->isSearchable());
    }
}
