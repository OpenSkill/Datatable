<?php

namespace OpenSkill\Datatable;

use OpenSkill\Datatable\Columns\ColumnConfiguration;
use OpenSkill\Datatable\Providers\Provider;
use OpenSkill\Datatable\Versions\Version;
use OpenSkill\Datatable\Versions\VersionEngine;
use OpenSkill\Datatable\Views\DatatableView;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class DatatableService
 * @package OpenSkill\Datatable
 *
 * The finalized and built DatatableService that can be used to handle a request or can be passed to the view.
 */
class DatatableService
{
    /** @var Provider */
    private $provider;

    /** @var ColumnConfiguration[] */
    private $columnConfigurations;

    /** @var VersionEngine */
    private $versionEngine;

    /**
     * DatatableService constructor.
     * @param Provider $provider The provider that will prepare the data
     * @param ColumnConfiguration[] $columnConfigurations
     * @param VersionEngine $versionEngine
     */
    public function __construct(Provider $provider, $columnConfigurations, VersionEngine $versionEngine)
    {
        $this->provider = $provider;
        $this->columnConfigurations = $columnConfigurations;
        $this->versionEngine = $versionEngine;
    }

    /**
     * @param Version $version The version that should be used to generate the view and the responses
     */
    public function setVersion(Version $version)
    {
        $this->versionEngine->setVersion($version);
    }

    /**
     * @return bool True if any version should handle the current request
     */
    public function shouldHandle()
    {
        return $this->versionEngine->hasVersion();
    }

    /**
     * Will handle the current request and returns the correct response
     */
    public function handleRequest()
    {
        $version = $this->versionEngine->getVersion();
        $queryConfiguration = $version->parseRequest($this->columnConfigurations);
        $this->provider->prepareForProcessing($queryConfiguration, $this->columnConfigurations);
        $data = $this->provider->process();
        return $version->createResponse($data, $queryConfiguration, $this->columnConfigurations);
    }

    /**
     * @param string $view the view to use or null if the standard view should be used for the table and the script
     */
    public function view($view = null)
    {
        new DatatableView($view, $this->versionEngine->getVersion(), $this->columnConfigurations);
    }
}