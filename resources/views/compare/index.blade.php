@extends( 'layout.app' )

@section( 'content' )

<div class="panel panel-primary">
  <div class="panel-header">
    <h4>Slice Comparison</h4>
  </div>
  <div class="panel-body">
    <form id="slice-comparison">
      <div class="form-group">
        <label>Slice 1</label>
        <select id="select-comparison-slice1" name="slice1" class="form-control select-slice">
          <option value="">( Select a slice. )</option>
        </select>
      </div>
      <div class="form-group">
        <label class="col-2">Slice 2</label>
        <select id="select-comparison-slice2" name="slice2" class="form-control select-slice">
          <option value="">( Select a slice. )</option>
        </select>
      </div>
    </form>
  </div>
</div>

<div id="comparison-chart">
</div>

<script>
    jPost.slices.forEach(
        function( slice ) {
            var name = slice.name;
            var tag = '<option>' + name + '</option>';
            $( '#select-comparison-slice1' ).append( tag );
            $( '#select-comparison-slice2' ).append( tag );
        }
    )
    $( '#select-comparison-slice1' ).change( jPost.compareSlices );
    $( '#select-comparison-slice2' ).change( jPost.compareSlices );
</script>

@endsection
