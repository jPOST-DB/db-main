<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// dataset controller
class DatasetController extends Controller {

    /** index */
    public function index( Request $request ) {
        $id = $request->input( 'id' );
        $params = array( 'id' => $id, 'name' => 'dataset' );

        $slice = $request->input( 'slice' );
        $params[ 'slice' ] = ( $slice == null ? '' : $slice );

        $view = view( 'dataset.index',  $params );
        return $view;
    }
}
