@extends( 'layout.app' )

@section( 'content' )

<h2>Protein: {{ $id }}</h2>
<div id="table_protein"></div>

<h3>Protein Browser</h3>
<div id="protein_browser"></div>

<h3>Peptide Sharing</h3>
<div id="proteoform_browser"></div>

<ul class="nav nav-tabs">
  <li class="nav-item active"><a class="nav-link bg-primary" href="#table-tab-peptide" data-toggle="tab">Peptide</a></li>
  <li class="nav-item"><a class="nav-link bg-primary" href="#table-tab-psm"  data-toggle="tab">Psm</a></li>
</ul>
<div class="tab-content">
  <div class="tab-pane fade in active table-panel" id="table-tab-peptide">
    <form id="peptide-form" onsubmit="return false;">
      <div id="table-peptide" class="display"></div>
    </form>
  </div>
  <div class="tab-pane fade table-panel" id="table-tab-psm">
    <form id="psm-form" onsubmit="return false;">
      <div id="table-psm" class="display"></div>
    </form>
  </div>
</div>

<script>
    jPost.setSlice();

    var id = '{{ $id }}';

    jPost.sets[ 'proteins' ] = [ id ];

    jPost.createPeptideTable();
    jPost.createPsmTable();

    var stanzas = [
        {
            name: 'table_protein',
            id: 'table_protein',
            data: function() {
                return {
                    uniprot: id,
                    dataset: jPost.sets.datasets.join( ' ' )
                };
            }
        },
        {
            name: 'protein_browser',
            id: 'protein_browser',
            data: function() {
                return {
                    uniprot: id,
                    dataset: jPost.sets.datasets.join( ' ' )
                };
            }
        },
        {
            name: 'proteoform_browser',
            id: 'proteoform_browser',
            data: function() {
                return {
                    uniprot: id,
                    dataset: jPost.sets.datasets.join( ' ' )
                };
            }
        }
    ];

    jPost.setStanzas( stanzas );
    jPost.loadStanzas();

</script>


@endsection
