@extends( 'layout.app' )

@section( 'content' )

<h2>Dataset: {{ $id }}</h2>
<div id="table_dataset"></div>

<h3>Chromosome Info.</h3>
<div id="chromosome_histogram"></div>

<h3>Protein Existence</h3>
<div id="protein_evidence"></div>

<h3>KEGG Pathway Mapping</h3>
<div id="kegg_mapping_form"></div>


<ul class="nav nav-tabs">
  <li class="nav-item active"><a id="tab-protein" class="nav-link bg-primary" href="#table-tab-protein" data-toggle="tab">Protein</a></li>
  <li class="nav-item"><a id="tab-peptide" class="nav-link bg-primary" href="#table-tab-peptide"  data-toggle="tab">Peptide</a></li>
</ul>
<script>
  $( '#tab-protein' ).on( 'click', function() { $( '#protein' ).tabulator( 'setData' ); } );
  $( '#tab-peptide' ).on( 'click', function() { $( '#peptide' ).tabulator( 'setData' ); } );
</script>
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
    jPost.setSlice();

    var id = '{{ $id }}';
    jPost.sets[ 'datasets' ] = [ id ];
    
    var stanzas = [
        {
            name: 'table_dataset',
            id: 'table_dataset',
            data: function() {
                return { dataset: id };
            }
        },
        {
            name: 'kegg_mapping_form',
            id: 'kegg_mapping_form',
            data: function() {
                return { dataset: id }
            }
        },
        {
            name: 'chromosome_histogram',
            id: 'chromosome_histogram',
            data: function() {
                return { dataset: id }
            }
        },
        {
            name: 'protein_evidence',
            id: 'protein_evidence',
            data:  function() {
                return { dataset: id }
            }
        }
    ];

    jPost.setStanzas( stanzas );
    jPost.loadStanzas();

    jPost.createProteinTable( false );
    jPost.createPeptideTable();
</script>

@endsection
