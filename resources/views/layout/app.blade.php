<?php
    $pages = array( 'search', 'slices', 'compare', 'help' );
?>

<!DOCTYPE html>

<html>
  <head>
    <title>jPOST Database</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/tabulator.min.css" rel="stylesheet">
    <link href="css/select2.min.css" rel="stylesheet">
    <link href="css/jpost.css" rel="stylesheet">

    <script src="js/jquery-3.1.1.min.js"></script>
    <script src="js/jquery-ui.min.js"></script>
    <script src="js/jquery.numeric.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/tabulator.min.js"></script>
    <script src="js/select2.full.min.js"></script>
    <script src="js/FileSaver.min.js"></script>
    <script src="js/jpost.js"></script>
    <script src="js/jpost-slice.js"></script>
    <script>
        jPost.loadSlices();
    </script>
  </head>
  <body>
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index">jPOST Database</a>
        </div>
        <div class="collapse navbar-collapse" id="navbar">
          <ul id="header-menu" class="nav navbar-nav">
@foreach( $pages as $page )
    @if( $page == $name )
             <li class="active"><a href="{{ $page }}">{{ ucfirst( $page ) }}</a></li>
    @else
             <li><a href="{{ $page }}">{{ ucfirst( $page ) }}</a></li>
    @endif
@endforeach
          </ul>
        </div>
      </div>
      <div class="container-fluid slice-header" style="background-color: #008000; color: #ffffff;">
        <div class="navbar-header">
          <div class="dropdown" style="float: left;">
            <button type="button" id="dropdown-button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
              <span class="slice-title-name"></span>
              <span class="caret"></span>
            </button>
            <ul id="slice-menu" class="dropdown-menu" role="menu">
            </ul>
          </div>
        </div>
        <div id="slice-buttons" style="color: #ffffff; background-color: #008000;"></span>
      </div>
    </nav>
    <div style="height: 100px;"></div>
    <div class="slice-header" style="height: 75px;"></div>

    <input type="hidden" id="slice-name" value="{{ $slice }}" >

    <div class="container">
        @yield( 'content' )
    </div>

    <script>
      jPost.slices.forEach(
          function( slice ) {
              var tag = '<li role="presentation"><a href="javascript:jPost.selectSlice( '
                      + "'" + slice.name + "'" + ' )">' + slice.name + '</a></li>'
              $( '#slice-menu' ).append( tag );
          }
      );

      var slice = jPost.slice;
      if( slice !== null ) {
        $( '.slice-header' ).css( 'display', 'block' );
        $( '.slice-title-name' ).html( slice.name );
      }
    </script>

    <div id="dialog-slice-selection" class="modal fade dialog-slice-selection">
      <div class="modal-dialog dialog-slice-selection">
        <div class="modal-content" style="padding: 15px;">
          <div class="modal-header">
            <h4 class="modal-title">Select Slice</h4>
          </div>
          <div class="modal-body">
            <div class="form-group row">
              <label class="col-2">Slice</label>
              <select id="select-slice" name="slice" class="form-control col-10 dialog-slice-selection">
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default dialog-slice-selection" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary dialog-slice-selection" onClick="jPost.onCloseSliceSelectionDialog()">OK</button>
          </div>
        </div>
      </div>
    </div>

    <div id="dialog-slice-selection" class="modal fade dialog-slice-selection">
      <div class="modal-dialog dialog-slice-selection">
        <div class="modal-content" style="padding: 15px;">
          <div class="modal-header">
            <h4 class="modal-title">Select Slice</h4>
          </div>
          <div class="modal-body">
            <div class="form-group row">
              <label class="col-2">Slice</label>
              <select id="select-slice" name="slice" class="form-control col-10 dialog-slice-selection">
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default dialog-slice-selection" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary dialog-slice-selection" onClick="jPost.onCloseSliceSelectionDialog()">OK</button>
          </div>
        </div>
      </div>
    </div>

    <div id="dialog-slice" class="modal fade">
      <div class="modal-dialog">
        <div class="modal-content" style="padding: 15px;">
          <div class="modal-header">
            <h4 class="modal-title">New Slice</h4>
          </div>
          <div class="modal-body">
            <div class="form-group row">
              <label class="col-2">Slice Name</label>
              <input type="text" id="dialog-slice-name" name="name" required class="form-control col-10" value="">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" onClick="jPost.addNewSlice()">Add</button>
          </div>
        </div>
      </div>
    </div>

    <form id="common-form" method="get"
    </form>
    <div id="tmp" style="display: none;">
    </div>
    <div style="width: 100%; border-top: solid #cccccc 1px; margin-top: 15px; padding: 15px;">
        <a href="http://www.jst.go.jp/EN/"><img width="50" src="img/help/jst.png"></a>
        <a href="http://biosciencedbc.jp/en/"><img width="100" src="img/help/nbdc.png"></a>
        <p>© 2018, Japan Proteome Sandard repository/database (<a href="http://jpostdb.org/">jPOST</a>), All rights reserved.</p>
    </div>
  </body>
</html>
