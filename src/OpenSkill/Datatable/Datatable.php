<?php

namespace OpenSkill\Datatable;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\View\Factory;
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
     * @var Factory
     */
    private $viewFactory;

    /**
     * @var Repository
     */
    private $configRepository;

    /**
     * Datatable constructor.
     * @param VersionEngine $versionEngine The version engine that determines the correct version
     * @param Factory $viewFactory The factory used to handle the view generation
     * @param Repository $configRepository The repository responsible to get config values
     */
    public function __construct(VersionEngine $versionEngine, Factory $viewFactory, Repository $configRepository)
    {
        $this->versionEngine = $versionEngine;
        $this->viewFactory = $viewFactory;
        $this->configRepository = $configRepository;
    }

    /**
     * Will create a new DataComposer with the given provider as implementation.
     *
     * @param Provider $provider The provider for the underlying data.
     * @return ColumnComposer
     */
    public function make(Provider $provider)
    {
        $composer = new ColumnComposer($provider, $this->versionEngine, $this->viewFactory, $this->configRepository);
        return $composer;
    }

    /**
     * Will return a default DatatableView with no columns defined, but with the view and the version prepared.
     * The user is responsible to populate the view with the wished columns, because they can not be derived from
     * the server side column configuration.
     *
     * @param string $tableView the name of the table view to render
     * @param string $scriptView the name of the script view to render
     * @return DatatableView the view to work with
     * @internal param string $view the view for the table
     */
    public function view($tableView = null, $scriptView = null)
    {
        return new DatatableView($tableView, $scriptView, $this->versionEngine->getVersion(), $this->viewFactory, []);
    }
}