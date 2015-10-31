<?php

namespace OpenSkill\Datatable;

use OpenSkill\Datatable\Providers\DTProvider;
use OpenSkill\Datatable\Composers\DTDataComposer;
use Illuminate\Http\Request;
use OpenSkill\Datatable\Versions\DTVersionEngine;

/**
 * Class Datatable
 * @package OpenSkill\Datatable
 *
 * Main class for all data table related operations.
 */
class Datatable
{
    /**
     * @var DTVersionEngine
     */
    private $versionEngine;

    /**
     * Datatable constructor.
     * @param DTVersionEngine $versionEngine
     */
    public function __construct(DTVersionEngine $versionEngine)
    {
        $this->versionEngine = $versionEngine;
    }

    /**
     * Will create a new DTDataComposer with the given provider as implementation.
     *
     * @param DTProvider $provider The provider for the underlying data.
     * @return DTDataComposer
     */
    public function make(DTProvider $provider)
    {
        $composer = new DTDataComposer($provider);
        return $composer;
    }

    /**
     * Will determine if the Datatable should handle the current request.
     * This is normally used when just one route is active for the view and the json data.
     *
     * @return boolean true, if the plugin should handle this request, false otherwise
     */
    public function shouldHandle() {
        return $this->versionEngine->hasVersion();
    }
}