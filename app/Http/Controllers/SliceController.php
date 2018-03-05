<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SliceController extends Controller {
    /** index */
    public function index( Request $request ) {
          $params = array();
          $params[ 'name' ] = 'slices';

          $slice = $request->input( 'slice' );
          if( $slice === null ) {
              $slice = '';
          }
          $params[ 'slice' ] = $slice;
          
          $view = view( 'slice.index', $params );
          return $view;
    }
}
