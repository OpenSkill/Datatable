<?php

namespace OpenSkill\Datatable\Responses;


use Illuminate\Support\Collection;
use OpenSkill\Datatable\Columns\ColumnConfigurationBuilder;
use OpenSkill\Datatable\Data\ResponseData;
use OpenSkill\Datatable\Queries\QueryConfigurationBuilder;

class Datatable19ResponseCreatorTest extends \PHPUnit_Framework_TestCase
{

    public function testResponseConstruction()
    {
        $data = [
            ['id' => 1, 'name' => 'fooBar'],
            ['id' => 2, 'name' => 'BazQua'],
        ];

        $queryConfiguration = QueryConfigurationBuilder::create()
            ->build();

        $columnConfiguration = ColumnConfigurationBuilder::create()
            ->name('id')
            ->build();

        $respData = new ResponseData(new Collection($data), 123);

        $rsp = new Datatable19ResponseCreator();
        $response = $rsp->createResponse($respData, $queryConfiguration, [$columnConfiguration]);

        $this->assertSame(200, $response->getStatusCode());

        $body = json_decode($response->getContent());

        $this->assertSame(1, $body->sEcho);
        $this->assertSame(123, $body->iTotalRecords);
        $this->assertSame(2, $body->iTotalDisplayRecords);

    }
}
