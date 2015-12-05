<?php

namespace packages\OpenSkill\Datatable\tests\OpenSkill\Datatable\Columns\Searchable;

use OpenSkill\Datatable\Columns\Searchable\Searchable;

class DefaultSearchableTest extends \PHPUnit_Framework_TestCase
{
    public function testClass()
    {
        $t = Searchable::NORMAL();
        $this->assertTrue($t->isSearchable());
    }
}
