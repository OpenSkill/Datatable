<?php

namespace packages\OpenSkill\Datatable\tests\OpenSkill\Datatable\Columns\Searchable;

use OpenSkill\Datatable\Columns\Searchable\Searchable;

class RegexSearchableTest extends \PHPUnit_Framework_TestCase
{
    public function testClass()
    {
        $t = Searchable::REGEX();
        $this->assertTrue($t->isSearchable());
    }
}
