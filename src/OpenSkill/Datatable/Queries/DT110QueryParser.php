<?php

namespace OpenSkill\Datatable\Queries;

use Illuminate\Http\Request;
use OpenSkill\Datatable\Columns\ColumnConfiguration;
use OpenSkill\Datatable\Interfaces\DTData;
use OpenSkill\Datatable\Interfaces\DTQueryConfiguration;
use Symfony\Component\HttpFoundation\Response;

class DT110QueryParser implements DTQueryParser
{

    /**
     * Method to determine if this parser can handle the query parameters. If so then the parser should return true
     * and be able to return a DTQueryConfiguration
     *
     * @param Request $request the current request the parse should analyse
     *
     * @return bool true if the parser is able to parse the query parameters and to return a DTQueryConfiguration
     */
    public function canParse(Request $request)
    {
        // check if draw is set and sEcho not
        return !$request->query->has("sEcho") && $request->query->has("draw");
    }

    /**
     * Responsible to create a response with the given data, that conforms to the data table request.
     *
     * @param DTData $data The data to return
     * @return Response the response
     */
    public function respond(DTData $data)
    {
        // TODO: Implement respond() method.
    }

    /**
     * Method that should parse the request and return a DTQueryConfiguration
     *
     * @param Request $request the current request to analyse
     *
     * @return DTQueryConfiguration the configuration the provider can use to prepare the data
     * @param ColumnConfiguration[] $columnConfiguration The configuration of the columns
     */
    public function parse(Request $request, array $columnConfiguration)
    {
        // TODO: Implement parse() method.
    }
}