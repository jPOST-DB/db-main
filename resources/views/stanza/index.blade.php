<!DOCTYPE html>

<html>
  <head>
    <title>{{ $stanza }} - jPOST Stanza</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <script src="{{ $service }}/stanza/assets/components/webcomponentsjs/webcomponents-loader.js"></script>
    <link href="{{ $service }}/stanza/{{ $stanza }}/" rel="import">
  </head>
  <body>
    <togostanza-{{ $stanza }}
@foreach( $attributes as $key => $value )
      {{ $key }}="{{ $value }}"
@endforeach
    ></togostanza-{{ $stanza }}>
  </body>
</html>
