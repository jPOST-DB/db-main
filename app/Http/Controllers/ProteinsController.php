<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libs\Config;
use App\Libs\SparqlTool;
use App\Libs\HtmlTool;

class ProteinsController extends Controller {
    // index
    public function index( Request $request ) {
        $page = array();
        $url = Config::$SPARQLIST_URL . 'dbi_protein_table';
        $sliceFilters = array( 'datasets',  'excluded_datasets', 'excluded_proteins' );
        $filters = array(
            'species', 'species_s', 'sample_type', 'cell_line', 'organ',
            'disease', 'disease_s', 'modification', 'instrument',
            'dataset_keywords', 'protein_keywords',
        );

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

        HtmlTool::setPageParameters( $parameters, $request, array( 'sequence_length' => 'length' ) );
        $parameters[ 'line_count' ] = '';
        $result = SparqlTool::postSparqList( $url, $parameters );

        $array = array();

        foreach( $result as $element ) {
            $accession = $element[ 'accession' ];
            $name = $element[ 'full_name' ];

            $element[ 'accession' ] = '<a href="http://www.uniprot.org/uniprot/' . $accession . '">' . $accession . '</a>';
            $element[ 'full_name' ] = '<a href="javascript:jPost.openProtein(' . "'" . $accession . "'" . ')">' . $name . '</a>';
            $element[ 'sequence_length' ] = $element[ 'length' ];
            $element[ 'check'] = '<input type="checkbox" class="check_protein" name="proteins" value="' . $accession . '">';

            array_push( $array, $element );
        }

        HtmlTool::setPageInfo( $page, $request, $total, $count, $array );

        return $page;
    }
}
