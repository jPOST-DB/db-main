@extends( 'layout.app' )

@section( 'content' )

<h2>Dataset: {{ $id }}</h2>
<div id="table_dataset"></div>

<h3>Chromosome Info.</h3>
<div id="dataset_chromosome"></div>

<h3>Protein Existence</h3>
<div id="protein_evidence"></div>

<h3>KEGG Pathway Mapping</h3>
<div id="kegg_mapping_form"></div>


<ul class="nav nav-tabs">
  <li class="nav-item active"><a class="nav-link bg-primary" href="#table-tab-protein" data-toggle="tab">Protein</a></li>
  <li class="nav-item"><a class="nav-link bg-primary" href="#table-tab-peptide"  data-toggle="tab">Peptide</a></li>
</ul>
<div class="tab-content">
  <div class="tab-pane fade in active table-panel" id="table-tab-protein">
    <form id="protein-form" onsubmit="return false;">
      <div id="table-protein" class="display"></div>
    </form>
  </div>
  <div class="tab-pane fade table-panel" id="table-tab-peptide">
    <form id="peptide-form" onsubmit="return false;">
      <div id="table-peptide" class="display"></div>
    </form>
  </div>
</div>

<script>
    var stanzas = [
        'table_dataset', 'kegg_mapping_form', 'dataset_chromosome', 'protein_evidence'
    ];

    stanzas.forEach(
        function( stanza ) {
            var url = 'stanza?stanza=' + stanza + '&datasets={{ $id }}&dataset={{ $id }}';
            $( '#' + stanza ).load( url );
        }
    );

    jPost.sets[ 'datasets' ] = [ '{{ $id }}' ];

    jPost.setSlice( false );
    jPost.createProteinTable( false );
    jPost.createPeptideTable();
</script>


@endsection
