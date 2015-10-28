<?php

namespace OpenSkill\Datatable\Facades;

use Illuminate\Support\Facades\Facade;

class DatatableFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'OpenSkill\Datatable\Datatable';
    }
}