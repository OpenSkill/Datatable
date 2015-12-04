<?php

namespace OpenSkill\Datatable;

use Illuminate\Http\Request;
use OpenSkill\Datatable\Columns\ColumnConfiguration;
use OpenSkill\Datatable\Providers\Provider;
use OpenSkill\Datatable\Versions\VersionEngine;

/**
 * Class DatatableService
 * @package OpenSkill\Datatable
 *
 * The finalized and built DatatableService that can be used to handle a request or can be passed to the view.
 */
class DatatableService
{
    /** @var Request */
    private $request;

    /** @var Provider */
    private $provider;

    /** @var ColumnConfiguration[] */
    private $columnConfigurations;

    /** @var VersionEngine */
    private $versionEngine;

    /**
     * DatatableService constructor.
     * @param Request $request The current request that should be handled
     * @param Provider $provider The provider that will prepare the data
     * @param ColumnConfiguration[] $columnConfigurations
     * @param VersionEngine $versionEngine
     */
    public function __construct(Request $request, Provider $provider, $columnConfigurations, VersionEngine $versionEngine)
    {
        $this->request = $request;
        $this->provider = $provider;
        $this->columnConfigurations = $columnConfigurations;
        $this->versionEngine = $versionEngine;
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
        $queryConfiguration = $version->queryParser()->parse($this->columnConfigurations);
        $this->provider->prepareForProcessing($queryConfiguration);
        $data = $this->provider->process($this->columnConfigurations);
        return $version->responseCreator()->createResponse($data, $queryConfiguration, $this->columnConfigurations);
    }

    /**
     * @param string $view
     */
    public function view($view = null)
    {

    }
}