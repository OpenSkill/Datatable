<?php

namespace packages\OpenSkill\Datatable\tests\OpenSkill\Datatable\Columns\Searchable;


use OpenSkill\Datatable\Columns\Searchable\DefaultSearchable;

class DefaultSearchableTest extends \PHPUnit_Framework_TestCase
{
    public function testClass()
    {
        $t = new DefaultSearchable();
        $this->assertTrue($t->isSearchable());
    }
}
