<?php

namespace packages\OpenSkill\Datatable\tests\OpenSkill\Datatable\Data;


use Illuminate\Support\Collection;
use OpenSkill\Datatable\Data\ResponseData;

class ResponseDataTest extends \PHPUnit_Framework_TestCase
{

    public function testResponseDataCreation()
    {
        $rp = new ResponseData(new Collection([]), 123);

        $this->assertSame(123, $rp->totalDataCount());
        $this->assertEquals(new Collection([]), $rp->data());
    }
}
