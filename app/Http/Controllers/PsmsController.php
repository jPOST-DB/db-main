<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libs\Config;
use App\Libs\SparqlTool;
use App\Libs\HtmlTool;

class PsmsController extends Controller{
    // index
    public function index( Request $request ) {
        $page = array();
        $url = Config::$SPARQLIST_URL . 'dbi_psm_table';
        $sliceFilters = array( 'datasets',  'proteins', 'peptides', 'excluded_datasets', 'excluded_proteins' );

        $sliceParameters = array( 'line_count' => 1 );
        foreach( $sliceFilters as $filter ) {
            $value = $request->input( $filter );
            if( $value !== null && $value !== '' ) {
                $sliceParameters[ $filter ] = $value;
            }
            else if( $filter === 'datasets' ) {
                $sliceParameters[ $filter ] = '';
            }
        }

        $result = SparqlTool::postSparqList( $url, $sliceParameters );
        $total = intval( $result[ 0 ][ 'line_count' ] );
        $count = $total;

        HtmlTool::setPageParameters( $sliceParameters, $request );
        $sliceParameters[ 'line_count' ] = '';
        $result = SparqlTool::postSparqList( $url, $sliceParameters );

        $array = array();

        foreach( $result as $element ) {
            array_push( $array, $element );
        }

        HtmlTool::setPageInfo( $page, $request, $total, $count, $array );

        return $page;
    }
}
