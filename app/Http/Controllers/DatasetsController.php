<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libs\Config;
use App\Libs\SparqlTool;
use App\Libs\HtmlTool;


class DatasetsController extends Controller {
    // index
    public function index( Request $request ) {
        $page = array();
        $url = Config::$SPARQLIST_URL . 'dbi_dataset_table';
        $sliceFilters = array( 'datasets',  'excluded_datasets', 'excluded_proteins' );
        $filters = array(
            'species', 'species_s', 'sample_type', 'cell_line', 'organ',
            'disease', 'disease_s', 'modification', 'instrument',
            'dataset_keywords', 'protein_keywords',
        );

        $class = $request->input( 'class' );
        if( $class === null || $class === '' ) {
            $class = 'check_dataset';
        }

        $parameters = array( 'line_count' => 1 );
        foreach( $sliceFilters as $filter ) {
            $value = $request->input( $filter );
            if( $value !== null && $value !== '' ) {
                $parameters[ $filter ] = $value;
            }
        }

        $result = SparqlTool::postSparqList( $url, $parameters );
        $total = intval( $result[ 0 ][ 'line_count' ] );

        foreach( $filters as $filter ) {
            $value = $request->input( $filter );
            if( $value !== null && $value !== '' ) {
                $parameters[ $filter ] = $value;
            }
        }

        $result = SparqlTool::postSparqList( $url, $parameters );
        $count = intval( $result[ 0 ][ 'line_count' ] );

        HtmlTool::setPageParameters( $parameters, $request );
        $parameters[ 'line_count' ] = '';
        $result = SparqlTool::postSparqList( $url, $parameters );

        $array = array();

        foreach( $result as $element ) {
            $id = $element[ 'dataset_id' ];
            $element[ 'dataset_id' ] = '<a href="javascript:jPost.openDataset(' . "'" . $id . "'" . ')">' . $id . '</a>';
            $element[ 'check' ] = '<input type="checkbox" class="' . $class . '" name="datasets" value="' . $id . '">';
            array_push( $array, $element );
        }

        HtmlTool::setPageInfo( $page, $request, $total, $count, $array );

        return $page;
    }
}
