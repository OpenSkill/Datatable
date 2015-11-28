<?php

namespace OpenSkill\Datatable\Versions;


use Illuminate\Http\Request;

/**
 * Class DTVersionEngine
 * @package OpenSkill\Datatable\Versions
 *
 * The DTVersionEngine will select the correct version based on the current request parameters. By default it will
 * support 1.9 and 1.10 of the datatable version.
 */
class VersionEngine
{
    /** @var Request */
    private $request;

    /** @var array */
    private $versions = [];

    /** @var Version The version for the request if it can be determined */
    private $version = null;

    /**
     * DTVersionEngine constructor.
     *
     * @param Request $request The current request
     * @param Version[] $versions an array of possible version this data table supports
     */
    public function __construct(Request $request, array $versions)
    {
        $this->request = $request;
        foreach ($versions as $version) {
            $this->versions[get_class($version)] = $version;
        }

        foreach ($versions as $v) {
            if ($v->queryParser()->canParse($request)) {
                $this->version = $v;
                break;
            }
        }
    }

    /**
     * @return Version Will return the version that is currently selected to handle the request.
     */
    public function getVersion() {
        return $this->version;
    }

    /**
     * @return bool true if one of the versions can handle the request, false otherwise
     */
    public function hasVersion() {
        return $this->version != null;
    }
}