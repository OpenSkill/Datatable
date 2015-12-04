<?php

namespace OpenSkill\Datatable;

use Illuminate\Http\Request;
use OpenSkill\Datatable\Composers\ColumnComposer;
use OpenSkill\Datatable\Providers\Provider;
use OpenSkill\Datatable\Versions\VersionEngine;

/**
 * Class Datatable
 * @package OpenSkill\Datatable
 *
 * Main class for all data table related operations.
 */
class Datatable
{
    /** @var VersionEngine */
    private $versionEngine;

    /** @var Request */
    private $request;

    /**
     * Datatable constructor.
     * @param Request $request The current request
     * @param VersionEngine $versionEngine The version engine that determines the correct version
     */
    public function __construct(Request $request, VersionEngine $versionEngine)
    {
        $this->request = $request;
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
        $composer = new ColumnComposer($provider, $this->versionEngine, $this->request);
        return $composer;
    }
}