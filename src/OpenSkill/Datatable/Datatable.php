<?php

namespace OpenSkill\Datatable;

use OpenSkill\Datatable\Providers\Provider;
use OpenSkill\Datatable\Composers\ColumnComposer;
use Illuminate\Http\Request;
use OpenSkill\Datatable\Versions\VersionEngine;

/**
 * Class Datatable
 * @package OpenSkill\Datatable
 *
 * Main class for all data table related operations.
 */
class Datatable
{
    /**
     * @var VersionEngine
     */
    private $versionEngine;

    /**
     * Datatable constructor.
     * @param VersionEngine $versionEngine
     */
    public function __construct(VersionEngine $versionEngine)
    {
        $this->versionEngine = $versionEngine;
    }

    /**
     * Will create a new DataComposer with the given provider as implementation.
     *
     * @param Provider $provider The provider for the underlying data.
     * @return ColumnComposer
     */
    public function make(Provider $provider)
    {
        $composer = new ColumnComposer($this->provider, $this->versionEngine);
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