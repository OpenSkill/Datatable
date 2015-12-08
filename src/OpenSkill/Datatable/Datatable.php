<?php

namespace OpenSkill\Datatable;

use OpenSkill\Datatable\Composers\ColumnComposer;
use OpenSkill\Datatable\Providers\Provider;
use OpenSkill\Datatable\Versions\VersionEngine;
use OpenSkill\Datatable\Views\DatatableView;
use Symfony\Component\HttpFoundation\RequestStack;

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

    /**
     * Datatable constructor.
     * @param VersionEngine $versionEngine The version engine that determines the correct version
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
        $composer = new ColumnComposer($provider, $this->versionEngine);
        return $composer;
    }

    /**
     * Will return a default DatatableView with no columns defined, but with the view and the version prepared.
     * The user is responsible to populate the view with the wished columns, because they can not be derived from
     * the server side column configuration.
     *
     * @param string $view the view for the table
     * @return DatatableView the view to work with
     */
    public function view($view = null)
    {
        return new DatatableView($view, $this->versionEngine->getVersion());
    }
}