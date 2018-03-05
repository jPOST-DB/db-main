<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libs\Config;
use App\Libs\SparqlTool;
use App\Libs\HtmlTool;

class PeptidesController extends Controller{
    // index
    public function index( Request $request ) {
        $page = array();
        $url = Config::$SPARQLIST_URL . 'dbi_peptide_table';
        $sliceFilters = array( 'datasets',  'proteins', 'excluded_datasets', 'excluded_proteins' );

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
        $total = intval( $result[ 0 ][ 'line_count'] );
        $count = $total;

        HtmlTool::setPageParameters( $sliceParameters, $request );
        $sliceParameters[ 'line_count' ] = '';
        $result = SparqlTool::postSparqList( $url, $sliceParameters );

        $array = array();

        foreach( $result as $element ) {
            $peptideId = $element[ 'peptide_id' ];
            $datasetId = $element[ 'dataset_id' ];
            $accession = $element[ 'accession' ];
            $name = $element[ 'full_name' ];

            $element[ 'peptide_id' ] = '<a href="peptide?id=' . $peptideId . '">' . $peptideId . '</a>';
            $element[ 'dataset_id' ] = '<a href="dataset?id=' . $datasetId . '">' . $datasetId . '</a>';
            $element[ 'accession' ] = '<a href="http://www.uniprot.org/uniprot/' . $accession . '">' . $accession . '</a>';
            $element[ 'full_name' ] = '<a href="protein?id=' . $accession . '">' . $name . '</a>';

            array_push( $array, $element );
        }

        HtmlTool::setPageInfo( $page, $request, $total, $count, $array );

        return $page;
    }
}
