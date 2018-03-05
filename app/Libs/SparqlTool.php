<?php

namespace App\Libs;

use App\Libs\Config;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;


/**
 *  SPARQL class
 */
class SparqlTool {
    public static function postSparqList( $url, $parameters ) {
        $client = new Client( [ RequestOptions::VERIFY => false ] );

        $response = $client->request(
            'POST',
            $url,
            [
               'form_params' => $parameters
            ]
        );
        $data = json_decode( $response->getBody(), true );

        $keys = $data[ 'head' ][ 'vars' ];
        $bindings = $data[ 'results' ][ 'bindings' ];

        $array = array();

        foreach( $bindings as $element ) {
            $object = array();
            foreach( $keys as $key ) {
                $object[ $key ] = $element[ $key ][ 'value' ];
            }

            array_push( $array, $object );
        }

        return $array;
    }
}

?>
