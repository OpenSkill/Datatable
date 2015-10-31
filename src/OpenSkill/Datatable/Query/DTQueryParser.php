<?php

namespace OpenSkill\Datatable\Query;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenSkill\Datatable\Columns\ColumnConfiguration;
use OpenSkill\Datatable\Interfaces\DTData;
use OpenSkill\Datatable\Interfaces\DTQueryConfiguration;
use Symfony\Component\HttpFoundation\Response;

/**
 * Interface DTQueryParser
 * @package OpenSkill\Datatable\Query
 *
 * Base interface that all parsers needs to implement.
 */
interface DTQueryParser
{
    /**
     * Method to determine if this parser can handle the query parameters. If so then the parser should return true
     * and be able to return a DTQueryConfiguration
     *
     * @param Request $request the current request the parse should analyse
     *
     * @return bool true if the parser is able to parse the query parameters and to return a DTQueryConfiguration
     */
    public function canParse(Request $request);

    /**
     * Method that should parse the request and return a DTQueryConfiguration
     *
     * @param Request $request the current request to analyse
     *
     * @return DTQueryConfiguration the configuration the provider can use to prepare the data
     * @param ColumnConfiguration[] $columnConfiguration The configuration of the columns
     */
    public function parse(Request $request, array $columnConfiguration);


    /**
     * Responsible to create a response with the given data, that conforms to the data table request.
     *
     * @param DTData $data The data to return
     * @return Response the response
     */
    public function respond(DTData $data);
}