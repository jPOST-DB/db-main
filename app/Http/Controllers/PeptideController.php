<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PeptideController extends Controller {
    /** index */
    public function index( Request $request ) {
        $id = $request->input( 'id' );
        $params = array( 'id' => $id, 'name' => 'peptide' );
        $slice = $request->input( 'slice' );
        $params[ 'slice' ] = ( $slice === null ? '' : $slice );        
        $view = view( 'peptide.index',  $params );
        return $view;
    }
}
