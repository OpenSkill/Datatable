<?php

namespace packages\OpenSkill\Datatable\tests\OpenSkill\Datatable\Columns\Searchable;


use OpenSkill\Datatable\Columns\Searchable\RegexSearchable;

class RegexSearchableTest extends \PHPUnit_Framework_TestCase
{
    public function testClass()
    {
        $t = new RegexSearchable();
        $this->assertTrue($t->isSearchable());
    }
}
