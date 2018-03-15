// jPOST JavaScript file

// namespace
var jPost = {};

// objects
jPost.sets = {
    datasets: [],
		proteins: [],
		peptides: [],
		excluded_datasets: [],
		excluded_proteins: []
}

jPost.tables = [];
jPost.stanzas = [];

// create select
jPost.createSelect = function( element, item ) {
	  if( item == 'cell_line') {
			  item = 'cellLine';
		}
		else if( item == 'sample_type' ) {
			  item = 'sampleType';
		}

    element.select2(
			  {
				    ajax: {
						    url: 'sparqlist/dbi_get_preset',
								type: 'GET',
								data: function( params ) {
									  return { item: item };
								},
								processResults: function( result, params ) {
									  var array = result.map(
   						    	    function( object ) {
													  return { id: object.object, text: object.label };
												}
										);

										return { results: array }
								}
						},
						width: '100%',
						tags: true
				}
		);

		element.change( jPost.updateTables );
}

// create search form
jPost.createSearchForm = function() {
    var items = [
        'species', 'sample_type', 'cell_line', 'organ', 'disease',
				'modification', 'instrument'
		];

		items.forEach(
			  function( item ) {
				    jPost.createSelect( $( '#' + item ), item );
				}
		);

		$( '#dataset_keywords' ).change( jPost.updateTables );
		$( '#protein_keywords' ).change( jPost.updateTables );
}

// create table
jPost.createTable = function( element, params, id ) {
    var size = 25;

    var header = '<table><tr><td id="' + id + '-pagesize-field"></td>'
	       + '<td id="' + id + '-tableinfo" style="text-align: right;"></td>'
	       + '</tr></table>';
    var footer = '<table><tr><td id="' + id + '-pagenumber">Page: '
    	       + '<input type="text" id="' + id + '-page-number" style="width: 90px;">'
    	       + ' of <span id="' + id + '-max-page"></span> '
    	       + '<a href="javascript:jPost.onClickChangePageButton(' + "'" + id + "'" + ')"><img '
    	       + 'src="img/icon_update32.png" width="24" /></a></td>'
	           + '<td id="' + id + '-operation" style="text-align: right;"></td>'
	           + '</tr></table>';
    element.html( header );
		element.append( '<table id="' + id + '" class="table"></table>' );
		element.append( footer );

		var searchParameters = jPost.getSearchParameters();

		params.paginationSize = size;
		params.resizableColumns = true;
		params.tooltips = true;
  	params.pagination = 'remote',
  	params.layout = 'fitColumns',
		params.pageLoaded = jPost.onLoadPage( id );
  	params.ajaxSorting = true;
    params.ajaxFiltering = true;
    params.ajaxResponse = jPost.onTableResponse( id );
    params.paginationElement = $( '#' + id + '-operation' );

    $( '#' + id + '-page-number' ).numeric( { decimal: false, negative: false } );

		$( '#' + id + '-pagesize-field' ).html( 'Page Size: <select id="' + id + '-pagesize"></select>' );
		[ 5, 10, 25, 50, 75, 100 ].forEach(
		    function( page ) {
				    $( '#' + id + '-pagesize' ).append( '<option>' + page + '</option>' )
				}
		);

		$( '#' + id + '-pagesize' ).val( size );
		$( '#' + id + '-pagesize' ).change( jPost.onChangePageSize( id ) );

		$( '#' + id ).tabulator( params );
}

// on table response
jPost.onTableResponse = function( id ) {
    var result = function( url, params, response ) {
	      var page = response.current_page;
				var size = response.page_size;
				var total = response.total_count;
				var count = response.filtered_count;
				var pages = response.last_page;

				var first = ( page - 1 ) * size + 1;
				var last = Math.min( page * size, count );
				if( first > last ) {
				    first = last;
				}

				var message = 'Showing ' + first + ' to ' + last
	            	  	+ ' of ' + count + ' entries';
				if( total > count ) {
				  	message = message + ' (filtered from ' + total + ' entries)';
				}

				$( '#' + id + '-tableinfo' ).html( message );

				$( '#' + id + '-page-number' ).val( page );
				$( '#' + id + '-max-page ').html( pages );

				return response;
    };

		return result;
}

// on change page size
jPost.onChangePageSize = function( id ) {
		var result = function() {
		    var size = $( '#' + id + '-pagesize' ).val();
				$( '#' + id ).tabulator( 'setPageSize', size );
		};

		return result;
}

// on load page
jPost.onLoadPage = function( id ) {
    var result = function() {
		};

		return result;
}

// on click change page button
jPost.onClickChangePageButton = function( id ) {
    var maxPage = $( '#' + id ).tabulator( 'getPageMax' );
    var page = $( '#' + id + '-page-number' ).val();
    page = Math.max( 1, Math.min( maxPage, parseInt( page ) ) );
    $( '#' + id ).tabulator( 'setPage', page );
}


// toggle checked
jPost.toggleCheck = function( name ) {
    var checked = $( '#' + name ).prop( 'checked' );
    $( '.' + name ).prop( 'checked', checked );
}

//create dataset table
jPost.createDatasetTable = function( checked ) {
    var params = {
        columns: jPost.getDatasetColumns( checked ),
        ajaxURL: 'datasets',
        ajaxConfig: 'GET',
        initialSort: [
            { column: 'dataset_id', dir: 'asc' }
        ],
        ajaxParams: jPost.getSearchParameters(),
        tableBuilt: function() {
            $( '#check_dataset' ).change(
                function() {
                    jPost.toggleCheck( 'check_dataset' );
                }
            );
        }
    };

		jPost.createTable( $( '#table-dataset' ), params, 'dataset' );
		jPost.tables.push( 'dataset' );
}

// create excluded dataset table
jPost.createExcludedDatasetTable = function() {
    var columns = jPost.getDatasetColumns( false );
    columns.unshift(
			  {
            title: '<input type="checkbox" id="check_dataset_excluded">',
            field: 'check', headerSort: false, width: 50, align: 'left', formatter: 'html'
        }
    );

    var params = {
        columns: columns,
        ajaxURL: 'datasets',
        ajaxConfig: 'GET',
        initialSort: [
            { column: 'dataset_id', dir: 'asc' }
        ],
        tableBuilt: function() {
            $( '#check_dataset_excluded' ).change(
                function() {
                    jPost.toggleCheck( 'check_dataset_excluded' );
                }
            );
        },
        ajaxParams: {
            datasets: 'DS99_999999',
            class: 'check_dataset_excluded'
        }
    };

		jPost.createTable( $( '#table-dataset-excluded' ), params, 'dataset-excluded' );
}

// get dataset columns
jPost.getDatasetColumns = function( checked ) {
    var columns = [
			  {
            title: '<input type="checkbox" id="check_dataset">',
            field: 'check', headerSort: false, width: 50, align: 'left', formatter: 'html'
        },
			  { title: 'Dataset ID', field: 'dataset_id', minWidth: 100, formatter: 'html' },
				{ title: 'Project ID', field: 'project_id', minWidth: 100 },
				{ title: 'Project Title', field: 'project_title', minWidth: 200 },
				{ title: 'Project Date', field: 'project_date', minWidth: 100 },
				{ title: '#proteins', field: 'protein_count', width: 100, align: 'right' },
				{ title: '#spectra', field: 'spectrum_count', width: 100, align: 'right' }
		];

    if( !checked ) {
        columns.shift();
    }

    return columns;
}

// create protein table
jPost.createProteinTable = function( checked ) {
		var params = {
			  columns: jPost.getProteinColumns( checked ),
        ajaxURL: 'proteins',
        ajaxConfig: 'GET',
        initialSort: [
            { column: 'full_name', dir: 'asc' }
        ],
        ajaxParams: jPost.getSearchParameters(),
        tableBuilt: function() {
            $( '#check_protein' ).change(
                function() {
                    jPost.toggleCheck( 'check_protein' );
                }
            );
        }
		}

		jPost.createTable( $( '#table-protein' ), params, 'protein' );
		jPost.tables.push( 'protein' );
}

// create excluded protein table
jPost.createExcludedProteinTable = function() {
    var columns = jPost.getProteinColumns( false );
    columns.unshift(
			  {
            title: '<input type="checkbox" id="check_protein_excluded" onclick="jPost.toggleProteinCheck">',
            field: 'check', headerSort: false, width: 50, align: 'left', formatter: 'html'
        }
    );

		var params = {
			  columns: columns,
        ajaxURL: 'proteins',
        ajaxConfig: 'GET',
        initialSort: [
            { column: 'full_name', dir: 'asc' }
        ],
        tableBuilt: function() {
            $( '#check_protein' ).change(
                function() {
                    jPost.toggleCheck( 'check_protein' );
                }
            );
        },
        ajaxParams: {
            proteins: 'DS99_999999',
            class: 'check_protein_excluded'
        }
		}

		jPost.createTable( $( '#table-protein-excluded' ), params, 'protein-excluded' );
}

// get protein columsn
jPost.getProteinColumns = function( checked ) {
    var columns = [
			  {
            title: '<input type="checkbox" id="check_protein" onclick="jPost.toggleProteinCheck">',
            field: 'check', headerSort: false, width: 50, align: 'left', formatter: 'html'
        },
        { title: 'Protein Name', field: 'full_name', minWidth: 300, formatter: 'html' },
				{ title: 'Accession', field: 'accession', minWidth: 150, formatter: 'html' },
				{ title: 'ID', field: 'mnemonic', minWidth: 150 },
				{ title: 'Length', field: 'sequence_length', minWidth: 100, align: 'right' },
				{ title: 'Sequence', field: 'sequence', minWidth: 400 }
		];

    if( !checked ) {
        columns.shift();
    }

    return columns;
}

// create peptide table
jPost.createPeptideTable = function() {
		var params = {
			  columns: [
            { title: 'ID', field: 'peptide_id', minWidth: 150, formatter: 'html' },
						{ title: 'Dataset ID', field: 'dataset_id', minWidth: 100, formatter: 'html' },
						{ title: 'Protein Name', field: 'full_name', minWidth: 250, formatter: 'html' },
						{ title: 'Accession', field: 'accession', minWidth: 100, formatter: 'html' },
						{ title: 'Protein ID', field: 'mnemonic', minWidth: 150 },
						{ title: 'Sequence', field: 'sequence', minWidth: 300 }
				],
        ajaxURL: 'peptides',
        ajaxConfig: 'GET',
        ajaxParams: jPost.getSearchParameters(),
        initialSort: [
            { column: 'peptide_id', dir: 'asc' }
        ]
		}

		jPost.createTable( $( '#table-peptide' ), params, 'peptide' );
		jPost.tables.push( 'peptide' );
}

// create psm table
jPost.createPsmTable = function() {
		var params = {
			  columns: [
            { title: 'ID', field: 'psm_id', minWidth: 150 },
						{ title: 'jPOST Score', field: 'jpost_score', width: 150, align: 'right' },
						{ title: 'Charge', field: 'charge', width: 100, align: 'right' },
						{ title: 'Calculated Mass', field: 'calc_mass', width: 150, align: 'right' },
						{ title: 'Experimental Mass', field: 'exp_mass', width: 150, align: 'right' },
						{ title: 'Sequence', field: 'sequence', minWidth: 300 }
				],
        ajaxURL: 'psms',
        ajaxConfig: 'GET',
        ajaxParams: jPost.getSearchParameters(),
        initialSort: [
            { column: 'psm_id', dir: 'asc' }
        ]
		}

		jPost.createTable( $( '#table-psm' ), params, 'psm' );
		jPost.tables.push( 'psm' );
}

// update tables
jPost.updateTables = function() {
	  var searchParameters = jPost.getSearchParameters();
		console.log( searchParameters );
    jPost.tables.forEach(
		    function( table ) {
				    var url = $( '#' + table ).tabulator( 'getAjaxUrl' );
						$( '#' + table ).tabulator( 'setData', url, searchParameters );
				}
		);
}

// get search parameters
jPost.getSearchParameters = function() {
	  var searchParameters = {};

		var sets = [
			  'datasets', 'proteins', 'peptides', 'excluded_datasets', 'excluded_proteins'
		];

		sets.forEach(
		    function( set ) {
				    var array = jPost.sets[ set ];
						if( array.length > 0 ) {
						    searchParameters[ set ] = array.join( ',' );
						}
				}
		);

    var filters = [
	  		'species', 'sample_type', 'cell_line', 'organ', 'disease',
		  	'modification', 'instrument'
		];

		filters.forEach(
			  function( filter ) {
				    var values = $( '#' + filter ).val();

						if( values !== null && values !== undefined ) {
								var ids = [];
								var words = [];
								values.forEach(
							  		function( value ) {
								    		var index = value.indexOf( '_' );
												if( ( filter == 'species' || filter == 'disease' )  && index < 0 ) {
										    		words.push( value );
												}
												else {
											  		ids.push( value );
												}
										}
								);

								if( ids.length > 0 ) {
							  		searchParameters[ filter ] = ids.join( ',' );
								}
								if( words.length > 0 ) {
							  		searchParameters[ filter + '_s' ] = words.join( ',' );
								}
						}
				}
		);

		var keywords = [
		    'dataset_keywords', 'protein_keywords'
		];

		keywords.forEach(
			  function( keyword ) {
				    var value = $( '#' + keyword ).val();
						if( value !== null && value !== undefined && value !== '' ) {
								searchParameters[ keyword ] = value;
						}
				}
		);

		return searchParameters;
}

// get selected values
jPost.getCheckedArray = function( name ) {
    var array = [];
	  $( '.' + name + ':checked' ).map(
		    function() {
			      var value = $(this).val();
			      array.push( value );
		    }
	 );

	 return array;
}

// exclude datasets
jPost.excludeDatasets = function() {
    var array = jPost.getCheckedArray( 'check_dataset' );
    array.forEach(
        function( dataset ) {
            if( jPost.sets.excluded_datasets.indexOf( dataset ) < 0 ) {
                jPost.sets.excluded_datasets.push( dataset );
            }
        }
    );

    jPost.updateTables();
    jPost.updateExcludedDatasetTable();
}

// restore datasets
jPost.restoreDatasets = function() {
    var array = jPost.getCheckedArray( 'check_dataset_excluded' );
    array.forEach(
        function( dataset ) {
            var index = jPost.sets.excluded_datasets.indexOf( dataset );
            if( index >= 0 ) {
                jPost.sets.excluded_datasets.splice( index, 1 );
            }
        }
    );

    jPost.updateTables();
    jPost.updateExcludedDatasetTable();
}

// update exluded dataset table
jPost.updateExcludedDatasetTable = function() {
    var params = { class: 'check_dataset_excluded' };
    if( jPost.sets.excluded_datasets.length == 0 ) {
        params[ 'datasets' ] = 'DS99_99999';
    }
    else {
        params[ 'datasets' ] = jPost.sets.excluded_datasets.join( ',' );
    }

    $( '#dataset-excluded' ).tabulator( 'setData', 'datasets', params );
}

// exclude proteins
jPost.excludeProteins = function() {
    var array = jPost.getCheckedArray( 'check_protein' );
    array.forEach(
        function( protein ) {
            if( jPost.sets.excluded_proteins.indexOf( protein ) <  0 ) {
                jPost.sets.excluded_proteins.push( protein );
            }
        }
    );

    jPost.updateTables();
    jPost.updateExcludedProteinTable();
}

// update excluded protein table
jPost.updateExcludedProteinTable = function() {
    var params = { class: 'check_protien_excluded' };
    if( jPost.sets.excluded_proteins.length == 0 ) {
        params[ 'proteins' ] = 'P99999999999';
    }
    else {
        params[ 'proteins' ] = jPost.sets.excluded_proteins.join( ',' );
    }

    $( '#protein-excluded' ).tabulator( 'setData', 'proteins', params );
}

// submit page
jPost.submitPage = function( url, params ) {
    var form = $( '#common-form' ).attr( 'action', url );
    if( params !== null ) {
        for( key in params ) {
            var value = params[ key ];
            var tag = '<input type="hidden" name="' + key + '" value="' + value + '">';
            form.append( tag );
        }
    }
    form.submit();
}

// get date
jPost.getDate = function() {
    $( '#tmp' ).text( new Date() );
    var date = $( '#tmp' ).text();
    var index = date.indexOf( '(' );
    if( index > 0 ) {
        date = date.substring( 0, index ).trim();
    }

    return date;
}

// set stanzas
jPost.setStanzas = function( stanzas ) {
    jPost.stanzas = stanzas;
}

// load stanzas
jPost.loadStanzas = function() {
    jPost.stanzas.forEach(
        function( stanza ) {
            var url = 'stanza?stanza=' + stanza.name;
            var params = stanza.data();
            for( key in params ) {
                url += '&' + key + '=' + encodeURI( params[ key ] );
            }
            $( '#' + stanza.id ).load( url );            
        }
    );
}
