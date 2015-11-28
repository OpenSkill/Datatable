<?php

namespace OpenSkill\Datatable\Queries;

use Illuminate\Http\Request;
use OpenSkill\Datatable\Interfaces\Data;

class QueryEngine
{
    /**
     * @var QueryParser the parser responsible for the current request
     */
    protected $parser = null;

    /**
     * Default constructor, will select the first matching query parser for the request.
     *
     * @param Request $request the current request to analyse
     * @param QueryParser[] $parser an array of parser that could handle the request
     */
    public function __construct(Request $request, array $parser)
    {
        // foreach parser check if it can handle the request.
        // the parser which will return true first will be handling the request
        foreach ($parser as $p) {
            if ($p->canParse($request)) {
                $this->parser = $p;
                break;
            }
        }
    }

    /**
     * Will check if a parser was set to handle the request.
     *
     * @return bool true if the request can be handled, false otherwise
     */
    public function shouldHandle()
    {
        return $this->parser != null;
    }

    /**
     * Will return a response from the data if a parser was set.
     * Will throw an exception if no parser was set
     *
     * @param Data $data The data to return
     *
     * @return \Symfony\Component\HttpFoundation\Response the response to deliver to the frontend
     */
    public function createResponse(Data $data) {
        if($this->parser == null) {
            throw new \InvalidArgumentException("There is no parser that can handle the request.");
        }
        return $this->parser->respond($data);
    }

}