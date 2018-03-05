<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/** search controller */
class SearchController extends Controller {

    /** index */
    public function index( Request $request ) {
        $params = array(
            'name' => 'search', 'slice' => ''
        );

        $view = view( 'search.index', $params );
        return $view;
    }
}
