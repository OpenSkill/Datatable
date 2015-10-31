<?php

namespace OpenSkill\Datatable\Versions;


use Illuminate\Http\Request;

class DTVersionEngine
{

    /** @var Request */
    private $request;

    /** @var array */
    private $versions = [];

    /** @var DTVersion The version for the request if it can be determined */
    private $version = null;

    /**
     * DTVersionEngine constructor.
     *
     * @param Request $request The current request
     * @param DTVersion[] $versions an array of possible version this data table supports
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

    public function hasVersion() {
        return $this->version != null;
    }
}