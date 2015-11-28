<?php

namespace OpenSkill\Datatable;

/**
 * Class DatatableService
 * @package OpenSkill\Datatable
 *
 * The finalized and built DatatableService that can be used to handle a request or can be passed to the view.
 */
class DatatableService
{

    public function shouldHandle() {
        return false;
    }

    public function handleRequest() {

    }

    public function view($view = null) {

    }
}