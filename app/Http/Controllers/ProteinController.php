<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// protein controller
class ProteinController extends Controller {

    /** index */
    public function index( Request $request ) {
        $id = $request->input( 'id' );
        $params = array( 'id' => $id, 'name' => 'protein' );
        $slice = $request->input( 'slice' );
        $params[ 'slice' ] = ( $slice === null ? '' : $slice );

        $view = view( 'protein.index',  $params );
        return $view;
    }
}
