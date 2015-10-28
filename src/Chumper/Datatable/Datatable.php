<?php

namespace Chumper\Datatable;

use Chumper\Datatable\Providers\DTProvider;
use Chumper\Datatable\Composers\DTDataComposer;
use Illuminate\Http\Request;

class Datatable
{
    /**
     * @var Request
     */
    private $request;

    /**
     * Datatable constructor. Will be resolved by laravel and will inject the needed dependencies
     * @param Request $request The request object
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Will create a new DTDataComposer with the given provider as implementation.
     *
     * @param DTProvider $provider The provider for the underlying data.
     * @return DTDataComposer
     */
    public function make(DTProvider $provider)
    {
        return new DTDataComposer($provider);
    }

    /**
     * Will determine if the Datatable should handle the current request.
     * This is normally used when just one route is active for the view and the json data.
     *
     * The method will check if the current request is an ajax request and if the "sEcho" or "draw" parameter is set,
     * depending on the version of the databtable javascript.
     *
     * @return boolean true, if the plugin should handle this request, false otherwise
     */
    public function shouldHandle() {
        if($this->request->ajax()) {
            return true;
        }
        return false;
    }
}