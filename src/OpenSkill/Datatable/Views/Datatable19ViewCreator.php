<?php

namespace OpenSkill\Datatable\Views;


use Illuminate\View\View;
use OpenSkill\Datatable\Columns\ColumnConfiguration;

class Datatable19ViewCreator implements ViewCreator
{

    public function createTable(ColumnConfiguration $columnConfiguration)
    {
        // will return a string that was made from a view
//        \View::make()->render()
    }

    public function createJavascript(ColumnConfiguration $columnConfiguration)
    {
        // TODO: Implement createJavascript() method.
    }
}