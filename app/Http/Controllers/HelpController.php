<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HelpController extends Controller {
    /** index */
    public function index( Request $request ) {
        $params = array( 'name' => 'help', 'slice' => '' );
        $view = view( 'help.index',  $params );
        return $view;
    }
}
