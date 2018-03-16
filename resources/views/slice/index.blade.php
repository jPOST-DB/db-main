@extends( 'layout.app' )

@section( 'content' )

<?php
    $selects = array(
        array( 'text' => 'Species',      'item' => 'species' ),
        array( 'text' => 'Sample type',  'item' => 'sample_type' ),
        array( 'text' => 'Cell line',    'item' => 'cell_line' ),
        array( 'text' => 'Organ',        'item' => 'organ' ),
        array( 'text' => 'Disease',      'item' => 'disease' ),
        array( 'text' => 'Modification', 'item' => 'modification' ),
        array( 'text' => 'instrument',   'item' => 'instrument' )
    );
?>

<div class="panel panel-primary slice-container" style="min-width: 800px; margin: 0 auto;">
  <div class="panel-heading" style="background-color: #008000;">
    <h4 class="panel-title">
      <a data-toggle="collapse" href="#search-form" id="search-filter-title" style="text-decoration: none;">Filters <span id="filter-header-icon" class="glyphicon glyphicon-triangle-bottom">&nbsp;</span></a>
    </h4>
  </div>
  <div class="panel-body collapse.show" id="search-form">
    <form style="min-width: 400px; width: 95%; margin: 10px;">
@foreach( $selects as $select )
      <div class="form-group row">
        <label class="col-sm-2 col-form-label" for="{{ $select[ 'item' ] }}">{{ $select[ 'text' ] }}</label>
        <div class="col-sm-10">
          <select id="{{ $select[ 'item' ] }}" name="{{ $select[ 'item' ] }}[]" class="form-control search-control" size="1" style="display: none;" multiple>
          </select>
        </div>
      </div>
@endforeach
      <div class="form-group row">
        <label class="col-sm-2 col-form-label" for="dataset_keywords">Dataset Keyword</label>
        <div class="col-sm-10">
          <input id="dataset_keywords" name="dataset_keywords" class="form-control search-control" placeholder="Dataset keywords (comma separated values)" style="border: 1px solid #aaaaaa; border-radius: 4px; width: 100%;">
        </div>
      </div>
      <div class="form-group row">
        <label class="col-sm-2 col-form-label" for="protein_keywords">Protein Keyword</label>
        <div class="col-sm-10">
          <input id="protein_keywords" name="protein_keywords" placeholder="Protein keywords (comma separated values)" class="form-control search-control" style="border: 1px solid #aaaaaa; border-radius: 4px; width: 100%;">
        </div>
      </div>
    </form>
  </div>
</div>

<div>
  <div id="message"></div>
  <div id="slice-buttons-empty" style="color: #000000 !important;"></div>
</div>

<div class="tab-content slice-container">
  <div id="table_slice"></div>

  <h3>Chromosome Info.</h3>
  <div id="chromosome_histogram"></div>

  <h3>Protein Existence</h3>
  <div id="protein_evidence"></div>

  <h3>KEGG Pathway Mapping</h3>
  <div id="kegg_mapping_form"></div>

  <ul class="nav nav-tabs" style="margin-top: 25px;">
    <li class="nav-item active"><a class="nav-link bg-primary" href="#table-tab-dataset" data-toggle="tab">Dataset</a></li>
    <li class="nav-item"><a class="nav-link bg-primary" href="#table-tab-protein"  data-toggle="tab">Protein</a></li>
  </ul>
  <div class="tab-content">
    <div class="tab-pane fade in active table-panel" id="table-tab-dataset">
      <form id="dataset-form" onsubmit="return false;">
        <div id="table-dataset" class="display"></div>
      </form>
      <div>
        <button class="btn" onclick="jPost.removeDatasets()">Remove from Slice</button>
      </div>
    </div>
    <div class="tab-pane fade table-panel" id="table-tab-protein">
      <form id="protein-form" onsubmit="return false;">
        <div id="table-protein" class="display"></div>
      </form>
    </div>
  </div>
</div>

<div id="dialog-rename-slice" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content" style="padding: 15px;">
      <div class="modal-header">
        <h4 class="modal-title">Rename Slice</h4>
      </div>
      <div class="modal-body">
        <div class="form-group row">
          <label class="col-2">Slice Name</label>
          <input type="text" id="dialog-rename-slice-new-name" name="name" required class="form-control col-10" value="">
          <input type="hidden" id="dialog-rename-slice-old-name">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" onClick="jPost.renameSlice()">Rename</button>
      </div>
    </div>
  </div>
</div>

<input type="file" id="upload_slices" name="file" style="display: none;"></input>

<script>
    jPost.createSearchForm();
    $( '#search-form' ).collapse( 'hide' );
    $( '#search-form' ).collapse( 'show' );
    $( '#search-form' ).collapse( 'hide' );

    $( '#search-form' ).on(
        {
            'show.bs.collapse': function() {
                $( '#filter-header-icon' ).removeClass( 'glyphicon-triangle-bottom' ).addClass( 'glyphicon-triangle-top' );
            },
            'hide.bs.collapse': function() {
                $( '#filter-header-icon' ).removeClass( 'glyphicon-triangle-top' ).addClass( 'glyphicon-triangle-bottom' );
            }
        }
    );

    if( jPost.slices.length === 0 ) {
        $( '.slice-container' ).css( 'display', 'none' );
    }

    var name = $( '#slice-name' ).val();
    if( name === null || name === '' ) {
        if( jPost.slices.length > 0 ) {
            $( '#slice-name' ).val( jPost.slices[ 0 ].name );
        }
    }
    jPost.setSlice();
    var slice = jPost.slice;
    name = ( slice === null ? '' : slice.name );

    jPost.createDatasetTable( true );
    jPost.createProteinTable( true );

    var tag = '<a href="javascript:jPost.exportAllSlices()" '
            + 'title="Export all slices.">'
            + '<span class="slice-icon glyphicon glyphicon-export">&nbsp;</span></a>';
    if( jPost.slices.length > 0 ) {
        $( '#slice-buttons' ).append( tag );
    }


    if( jPost.slices.length === 0 ) {
        tag = '<a href="javascript:jPost.importSlices()"  style="text-decoration: none; color: #000000;" '
            + 'title="Import slices.">&nbsp;&nbsp&nbsp;<span class="glyphicon glyphicon-import">&nbsp;</span></a>';
        $( '#message' ).html( '<span>There is no slice.</span>' + tag );
    }
    else {
        tag = '<a href="javascript:jPost.importSlices()" '
            + 'title="Import slices."><span class="slice-icon glyphicon glyphicon-import">&nbsp;</span></a>';
        $( '#slice-buttons' ).append( tag );
        $( '#slice-buttons' ).append( '<span>&nbsp;&nbsp;&nbsp;</span>' );
    }

    tag = '<a href="javascript:jPost.exportSlice()" '
            + 'title="Export the slice."><span class="slice-icon glyphicon glyphicon-export">&nbsp;</span></a>';
    $( '#slice-buttons' ).append( tag );

    tag = '<a href="javascript:jPost.openRenameDialog()" '
        + ' )" title="Rename the slice."><span class="slice-icon glyphicon glyphicon-edit">&nbsp;</span></a>';
    $( '#slice-buttons' ).append( tag );

    tag = '<a href="javascript:jPost.deleteSlice()" '
        + ' )" title="Remove the slice."><span class="slice-icon glyphicon glyphicon-trash">&nbsp;</span></a>';
    $( '#slice-buttons' ).append( tag );

    var stanzas = [
        {
            name: 'table_slice',
            id: 'table_slice',
            data: function() {
                return { dataset: jPost.sets.datasets.join( ' ' ) }
            }
        },
        {
            name: 'kegg_mapping_form',
            id: 'kegg_mapping_form',
            data: function() {
                return { dataset: jPost.sets.datasets.join( ' ' ) }
            }
        },
        {
            name: 'dataset_chromosome',
            id: 'dataset_chromosome',
            data: function() {
                return { dataset: jPost.sets.datasets.join( ' ' ) }
            }
        },
        {
            name: 'protein_evidence',
            id: 'protein_evidence',
            data: function() {
                return { dataset: jPost.sets.datasets.join( ' ' ) }
            }
        }
    ];
    jPost.setStanzas( stanzas );
    jPost.loadStanzas();

    $( '#upload_slices' ).on(
        'change',
        function( event ) {
            var file = $(this).prop( 'files' )[ 0 ];
            var reader = new FileReader();
            reader.readAsText( file );
            reader.onload = function( event ) {
                var result = JSON.parse( event.target.result );
                var flag = true;
                for( var i = 0; i < result.length && flag; i++ ) {
                    var slice = result[ i ];
                    jPost.slices.forEach(
                        function( tmp ) {
                            if( slice.name == tmp.name ) {
                                if( flag ) {
                                    flag = false;
                                    alert( 'Slice "' + slice.name + '" already exists.' );
                                }
                            }
                        }
                    );
                }
                if( !flag ) {
                    return;
                }
                result.forEach(
                    function( slice ) {
                        jPost.slices.push( slice );
                    }
                );
                jPost.saveSlices();
                location.reload();
            }
        }
    );

</script>


@endsection
