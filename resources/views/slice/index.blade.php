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
  <div class="panel-heading">
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

<div style="margin: 10pt 0">
    <button id="export-all-slices" class='btn' onclick="jPost.importSlices()">Import Slices</button>
    <button id="export-all-slices" class='btn' onclick="jPost.exportSlice( '' )">Export All Slices</button>
</div>

<ul id="slice-items" class="nav nav-tabs" style="margin-top: 25px;">
</ul>
<div class="tab-content slice-container">
  <div class="tab-pane fade in active table-panel" id="slice">


    <h3>KEGG Global Pathway</h3>
    <div id="kegg_global_map"></div>

    <h3>Chromosome Info.</h3>
    <div id="dataset_chromosome"></div>

    <h3>Protein Existence</h3>
    <div id="protein_evidence"></div>

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

    var name = '{{ $slice }}';
    var slice = jPost.getSlice( name );
    if( slice == null && jPost.slices.length > 0 ) {
        slice = jPost.slices[ 0 ];
        name = slice.name;
    }

    jPost.createSliceTabs( name );

    jPost.setSlice();
    jPost.createDatasetTable( true );
    jPost.createProteinTable( true );

    var stanzas = [
        'kegg_global_map', 'dataset_chromosome', 'protein_evidence'
    ];

    if( slice !== null && slice.datasets.length > 0 ) {
        var datasets = encodeURI( jPost.sets.datasets.join( ' ' ) );
        stanzas.forEach(
            function( stanza ) {
                var url = 'stanza?stanza=' + stanza + '&datasets=' + datasets + '&dataset=' + datasets;
                $( '#' + stanza ).load( url );
            }
        );
    }

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
