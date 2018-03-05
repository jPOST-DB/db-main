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
        array( 'text' => 'Instrument',   'item' => 'instrument' )
    );
?>

<div class="panel panel-primary" style="min-width: 800px; margin: 0 auto;">
  <div class="panel-heading">
    <h4 class="panel-title">
      <a data-toggle="collapse" href="#search-form" id="search-filter-title" style="text-decoration: none;">Filters <span id="filter-header-icon" class="glyphicon glyphicon-triangle-top">&nbsp;</span></a>
    </h4>
  </div>
  <div class="panel-body collapse.show" id="search-form">
    <form style="float: left; min-width: 400px; width: calc( 100% - 350px ); margin: 10px;">
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
    <div style="float: left; margin-left: 30px;">
      <div id="species-chart" style="margin: 20px;"></div>
      <div id="disease-chart" style="margin: 20px;"></div>
    </div>
    <div style="clear: both;"></div>
  </div>
</div>

<ul class="nav nav-tabs" style="margin-top: 25px;">
  <li class="nav-item active"><a class="nav-link bg-primary" href="#table-tab-dataset" data-toggle="tab">Dataset</a></li>
  <li class="nav-item"><a class="nav-link bg-primary" href="#table-tab-protein"  data-toggle="tab">Protein</a></li>
  <li class="nav-item"><a class="nav-link bg-primary" href="#table-tab-dataset-excluded"  data-toggle="tab">Excluded Dataset</a></li>
<!--
  <li class="nav-item"><a class="nav-link bg-primary" href="#table-tab-protein-excluded"  data-toggle="tab">Excluded Protein</a></li>
-->
</ul>
<div class="tab-content">
  <div class="tab-pane fade in active table-panel" id="table-tab-dataset">
    <form id="dataset-form" onsubmit="return false;">
      <div id="table-dataset" class="display"></div>
    </form>
    <div>
      <button class="btn" onclick="jPost.excludeDatasets()">Exclude</button>
      <button class="btn" onclick="jPost.addDatasetsToSlice()">Add to Slice</button>
    </div>
  </div>
  <div class="tab-pane fade table-panel" id="table-tab-protein">
    <form id="protein-form" onsubmit="return false;">
      <div id="table-protein" class="display"></div>
    </form>
    <button class="btn" onclick="jPost.excludeProteins()">Exclude</button>
  </div>
  <div class="tab-pane fade table-panel" id="table-tab-dataset-excluded">
    <form id="dataset-form" onsubmit="return false;">
      <div id="table-dataset-excluded" class="display"></div>
    </form>
    <div>
      <button class="btn" onclick="jPost.restoreDatasets()">Restore</button>
    </div>
  </div>
<!--
  <div class="tab-pane fade table-panel" id="table-tab-protein-excluded">
    <form id="protein-form" onsubmit="return false;">
      <div id="table-protein-excluded" class="display"></div>
    </form>
    <button class="btn" onclick="jPost.restoreProteins()">Restore</button>
  </div>
-->
</div>


<script>
    jPost.createSearchForm();
    $( '#search-form' ).collapse( 'hide' );
    $( '#search-form' ).collapse( 'show' );

	  var parameters = 'stanza=database_pie_chart&type=species';
	  var url = 'stanza?' + parameters;
    $( '#species-chart' ).load( url );
	  parameters = 'stanza=database_pie_chart&type=disease';
	  url = 'stanza?' + parameters;
	  $( '#disease-chart' ).load( url );

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

    jPost.createDatasetTable( true );
    jPost.createProteinTable( true );
    jPost.createExcludedDatasetTable();
//    jPost.createExcludedProteinTable();
</script>


@endsection
