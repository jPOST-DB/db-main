<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CompareController extends Controller {
    /** index */
    public function index( Request $request ) {
          $params = array(
              'name' => 'compare',
              'slice' => ''
          );
          $view = view( 'compare.index', $params );
          return $view;
    }
}
