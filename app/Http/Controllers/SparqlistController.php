<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libs\Config;
use App\Libs\SparqlTool;


class SparqlistController extends Controller {
    /** index */
    public function index( Request $request, $method ) {
        $url = Config::$SPARQLIST_URL . $method;
        $parameters = $request->all();
        $result = SparqlTool::postSparqList( $url, $parameters );

        return $result;
    }
}
