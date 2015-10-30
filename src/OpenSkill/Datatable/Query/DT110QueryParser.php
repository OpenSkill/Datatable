<?php
/**
 * Created by PhpStorm.
 * User: n.plaschke
 * Date: 29/10/15
 * Time: 22:58
 */

namespace OpenSkill\Datatable\Query;


use Illuminate\Http\Request;
use OpenSkill\Datatable\Interfaces\DTQueryConfiguration;

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
     * Method that should parse the request and return a DTQueryConfiguration
     *
     * @param Request $request the current request to analyse
     *
     * @return DTQueryConfiguration the configuration the provider can use to prepare the data
     */
    public function parse(Request $request)
    {
        // TODO: Implement parse() method.
    }
}