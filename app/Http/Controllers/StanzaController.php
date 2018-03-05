<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libs\Config;

/** stanza controller */
class StanzaController extends Controller {
    /** index */
    public function index( Request $request ) {
        $stanza = $request->input( 'stanza' );
        $service = $request->input( 'service' );
        if( $service == null || $service == '' ) {
            $service = Config::$DEFAULT_STANZA_SERVICE;
        }

        $attributes = $request->except( [ 'stanza', 'service' ] );

        $params = array(
            'stanza' => $stanza,
            'service' => $service,
            'attributes' => $attributes
        );

        $view = view( 'stanza.index', $params );
        return $view;
    }
}
