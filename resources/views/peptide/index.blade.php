@extends( 'layout.app' )

@section( 'content' )

<h2>Peptide: {{ $id }}</h2>
<div id="table_peptide"></div>

<ul class="nav nav-tabs">
  <li class="nav-item active"><a class="nav-link bg-primary" href="#table-tab-psm" data-toggle="tab">Psm</a></li>
</ul>
<div class="tab-content">
  <div class="tab-pane fade in active table-panel" id="table-tab-psm">
    <form id="psm-form" onsubmit="return false;">
      <div id="table-psm" class="display"></div>
    </form>
  </div>
</div>

<script>
    var stanzas = [
        'table_peptide'
    ];

    stanzas.forEach(
        function( stanza ) {
            var url = 'stanza?stanza=' + stanza + '&peptide={{ $id }}';
            $( '#' + stanza ).load( url );
        }
    );

    jPost.sets[ 'peptides' ] = [ '{{ $id }}' ];
    jPost.setSlice( false );
    jPost.createPsmTable();
</script>


@endsection
