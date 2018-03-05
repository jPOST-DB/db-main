<?php

namespace App\Libs;


// html tool
class HtmlTool {
    // set page information
    public static function setPageParameters( &$params, $request, $fieldMap = null ) {
        $page = $request->input( 'page' );
        $size = $request->input( 'size' );
        $sorter = $request->input( 'sorters' );

        if( $size !== null ) {
            $size = intval( $size );
            $params[ 'limit' ] = $size;
            if( $page !== null ) {
                $page = intval( $page );
                $params[ 'offset' ] = $size * ( $page - 1 );
            }
        }

        if( $sorter !== null ) {
            $sorter = $sorter[ 0 ];

            $order = $sorter[ 'field' ];
            $dir = $sorter[ 'dir' ];

            if( $fieldMap !== null ) {
                if( array_key_exists( $order, $fieldMap ) ) {
                    $order = $fieldMap[ $order ];
                }
            }

            $params[ 'order' ] = $order;
            if( $dir === 'desc' ) {
                $params[ 'desc' ] = 1;
            }
        }
    }

    // set page information
    public static function setPageInfo( &$result, $request, $total, $count, $data ) {
        $result[ 'total_count' ] = $total;
        $result[ 'filtered_count' ] = $count;
        $result[ 'data' ] = $data;

        $page = $request->input( 'page' );
        $size = $request->input( 'size' );

        if( $page !== null ) {
            $result[ 'current_page' ] = intval( $page );
        }

        if( $size !== null ) {
            $size = intval( $size );
            $result[ 'page_size' ] = $size;

            $last = max( 1, ceil( $count / $size ) );
            $result[ 'last_page' ] = $last;
        }
    }
}

?>
