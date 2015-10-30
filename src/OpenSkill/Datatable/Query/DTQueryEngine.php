<?php

namespace OpenSkill\Datatable\Query;

use Illuminate\Http\Request;

class DTQueryEngine
{
    private $shouldHandle = false;

    /**
     * @var DTQueryParser the parser responsible for the current request
     */
    protected $parser;

//
//    protected $version;
//
//    protected $echoValue;
//
//    /**
//     * @var VersionResponse
//     */
//    protected $response;

    /**
     * @param Request $request the current request to analyse
     * @param DTQueryParser[] $parser an array of parser that could handle the request
     */
    public function __construct(Request $request, array $parser)
    {
        // foreach parser check if it can handle the request.
        // the parser which will return true first will be handling the request
        foreach ($parser as $p) {
            if($p->canParse($request)) {
                $this->parser = $p;
                $this->shouldHandle = true;
                break;
            }
        }

//
//        $echo_value_old = $this->input->get('sEcho', null);
//        $echo_value_new = $this->input->get('draw', null);
//
//        if (is_null($echo_value_old) && is_null($echo_value_new)) {
//            $this->should_handle = false;
//        } else {
//            $this->should_handle = true;
//
//            // Don't handle the request if we are meant to serve responses for both v1.9 && v1.10.
//            if (!is_null($echo_value_old) && !is_null($echo_value_new)) {
//                $this->should_handle = false;
//                return;
//            }
//
//            if (!is_null($echo_value_old)) {
//                // Old (1.9) datatables
//                $this->version = Version::OLD_VERSION;
//                $this->echo_value = $echo_value_old;
//
//                $this->response = new Response\OldVersion();
//                $this->response->set_input($input);
//                $this->response->set_echo_value($this->echo_value);
//            } elseif (!is_null($echo_value_new)) {
//                // New (1.10) datatables
//                $this->version = Version::NEW_VERSION;
//                $this->echo_value = $echo_value_new;
//
//                $this->response = new Response\NewVersion();
//                $this->response->set_input($this->input);
//                $this->response->set_echo_value($this->echo_value);
//            }
//        }
    }

//    public function get_response_engine()
//    {
//        return $this->response;
//    }

    public function shouldHandle()
    {
        return $this->shouldHandle;
    }

}