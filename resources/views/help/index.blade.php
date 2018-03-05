@extends( 'layout.app' )

@section( 'content' )

<div id="help"></div>
<script>
  $( '#help' ).load( 'help.html' );
</script>


@endsection
