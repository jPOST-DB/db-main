@extends( 'layout.app' )

@section( 'content' )

<h2>Protein: {{ $id }}</h2>
<div id="table_protein"></div>

<h3>Protein Browser</h3>
<div id="protein_browser"></div>

<h3>Proteoforms</h3>
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
    var stanzas = [
        'table_protein', 'protein_browser', 'proteoform_browser'
    ];

    stanzas.forEach(
        function( stanza ) {
            var url = 'stanza?stanza=' + stanza + '&uniprot={{ $id }}';
            $( '#' + stanza ).load( url );
        }
    );

    jPost.sets[ 'proteins' ] = [ '{{ $id }}' ];

    jPost.setSlice();
    jPost.createPeptideTable();
    jPost.createPsmTable();
</script>


@endsection
