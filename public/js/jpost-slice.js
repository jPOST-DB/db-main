jPost.slices = [];
jPost.tmp = {
    datasets: null,
    slice: null
};

// get slice
jPost.getSlice = function( name ) {
    var slice = null;
    for( var i = 0; i < jPost.slices.length && slice === null; i++ ) {
        var tmp = jPost.slices[ i ];
        if( name === tmp.name ) {
            slice = tmp;
        }
    }

    return slice;
}

// create slice tabs
jPost.createSliceTabs = function( name ) {
    jPost.slices.forEach(
        function( slice ) {
            var tag = '';
            if( name === slice.name ) {
                tag = '<li class="nav-item active"><a class="nav-link bg-primary" href="slices?slice='
                    + slice.name + '">' + slice.name + '</a></li>';
            }
            else {
                tag = '<li class="nav-item"><a class="nav-link bg-primary" href="slices?slice='
                    + slice.name + '">' + slice.name + '</a></li>';
            }
            $( '#slice-items' ).append( tag );
        }
    );

    var tag = '<li class="nav-item"><a href="javascript:jPost.createNewSlice()">+</a></li>';
    $( '#slice-items' ).append( tag );
}

// open dataset
jPost.openDataset = function( id ) {
    var name = $( '#slice-name' ).val();
    var slice = jPost.getSlice( name );
    var params = { id : id };

    if( slice !== null ) {
        params.slice = slice.name;
        params.datasets = slice.datasets.join( ',' );
    }

    jPost.submitPage( 'dataset', params );
}

// open protein
jPost.openProtein = function( id ) {
    var name = $( '#slice-name' ).val();
    var slice = jPost.getSlice( name );
    var params = { id : id };

    if( slice !== null ) {
        params.slice = slice.name;
        params.datasets = slice.datasets.join( ',' );
    }

    jPost.submitPage( 'protein', params );
}

// open protein
jPost.openPeptide = function( id ) {
    var name = $( '#slice-name' ).val();
    var slice = jPost.getSlice( name );
    var params = { id : id };

    if( slice !== null ) {
        params.slice = slice.name;
        params.datasets = slice.datasets.join( ',' );
    }

    jPost.submitPage( 'peptide', params );
}

// open psm
jPost.openPeptide = function( id ) {
    var name = $( '#slice-name' ).val();
    var slice = jPost.getSlice( name );
    var params = { id : id };

    if( slice !== null ) {
        params.slice = slice.name;
        params.datasets = slice.datasets.join( ',' );
    }

    jPost.submitPage( 'psm', params );
}

// slice
jPost.setSlice = function() {
    var name = $( '#slice-name' ).val();
    var slice = jPost.getSlice( name );

    if( slice === null && jPost.slices.length > 0 ) {
        slice = jPost.slices[ 0 ];
    }

    if( slice !== null ) {
        slice.datasets.forEach(
            function( dataset ) {
                jPost.sets.datasets.push( dataset );
            }
        );
    }

    if( jPost.sets.datasets.length === 0 ) {
        jPost.sets.datasets.push( 'DS99_999999' );
    }
}

// add datasets to slice
jPost.addDatasetsToSlice = function() {
    jPost.tmp.datasets = null;
	  jPost.tmp.slice = null;

    var array = jPost.getCheckedArray( 'check_dataset' );
    if( array.length == 0 ) {
        alert( "No datasets are not selected. Please check one or more datasets." );
        return;
	  }

    var name = $( '#slice-name' ).val();
    var slice = jPost.getSlice();
    if( slice === null && jPost.slices.length > 0 ) {
        slice = jPost.slices[ 0 ];
    }

	  jPost.tmp.datasets = array;

    jPost.updateSliceSelection();
	  $( '#dialog-slice-selection' ).modal( 'show' );
	  if( jPost.slices.length == 0 ) {
		    jPost.openSliceDialog();
	  }
}

// update slice selection
jPost.updateSliceSelection = function() {
    var slice = jPost.getSlice();
    if( slice === null && jPost.slices.length > 0 ) {
        slice = jPost.slices[ 0 ];
    }

    if( slice == null ) {
		    $( '#select-slice' ).html( '<option value="" selected>+ (New Slice)</option>' );
	  }
	  else {
		    $( '#select-slice' ).html( '<option value="">+ (New Slice)</option>' );
	  }

	  jPost.slices.forEach(
		    function( tmp ) {
			       if( tmp.name === slice.name ) {
				         $( '#select-slice' ).append( '<option value="' + tmp.name + '" selected>' + tmp.name + '</option>' );
			       }
			       else {
				         $( '#select-slice' ).append( '<option value="' + tmp.name + '">' + tmp.name + '</option>' );
			       }
        }
    );

    $( '#select-slice' ).change(
        function() {
            var value = $( '#select-slice' ).val();
            if( value === '' ) {
                jPost.openSliceDialog();
            }
        }
    );
}

// open slice dialog
jPost.openSliceDialog = function() {
  	$( '#dialog-slice-name' ).val( '' );
  	$( '#slice-dialog-message' ).html( '' );

  	$( '#dialog-slice' ).modal( 'show' );
}

// add new slice
jPost.addNewSlice = function() {
    var found = false;
	  var name = $( '#dialog-slice-name' ).val();
	  jPost.slices.forEach(
		    function( slice ) {
			      if( slice.name == name ) {
				        found = true;
			      }
		    }
	  );

	  if( name.trim() == '' ) {
		    alert( 'Please enter the slice name.' );
	  }
	  else if( found ) {
	      alert( 'The specified slice name is already exists. Please enter another name.');
	  }
	  else {
	      jPost.createSlice( name );
		    if( jPost.tmp.datasets === null ) {
            window.location.href = 'slices?slice=' + name;
        }
        else {
			      var slice = jPost.getSlice( name );
            jPost.addSlice( slice, jPost.tmp.datasets );
        }
			  $( '#dialog-slice-selection' ).modal( 'hide' );
    }
		$( '#dialog-slice' ).modal( 'hide' );
}

// create slice
jPost.createSlice = function( name ) {
    jPost.slices.push(
        {
            name: name,
            operation: 'new',
            parameters: name,
            date: jPost.getDate(),
            datasets: [],
            base: {}
        }
    );
    jPost.saveSlices();
}

// add slices
jPost.addSlice = function( slice, datasets ) {
    slice.base = Object.assign( {}, slice );
    slice.operation = 'append';
    slice.parameters = jPost.getSearchParameters();
    slice.date = jPost.getDate();
    slice.datasets = [];

    slice.base.datasets.forEach(
        function( dataset ) {
            slice.datasets.push( dataset );
        }
    );

    datasets.forEach(
        function( dataset ) {
            var index = slice.datasets.indexOf( dataset );
            if( index < 0 ) {
                slice.datasets.push( dataset );
            }
        }
    );
    jPost.saveSlices();
}

// on close slice selection dialog
jPost.onCloseSliceSelectionDialog = function() {
    var name = $( '#select-slice' ).val();
    if( name == '' ) {
        alert( 'Select a slice.' );
	  }
	  else {
        var slice = jPost.getSlice( name );
		    if( slice !== null && jPost.tmp.datasets != null ) {
            jPost.addSlice( slice, jPost.tmp.datasets );
		    }
		    $( '#dialog-slice-selection' ).modal( 'hide' );
    }
}

// load slices
jPost.loadSlices = function() {
    var json = localStorage.getItem( 'jPOST-slices' );
		if( json != null ) {
        jPost.slices = JSON.parse( json );
    }
    console.log( jPost.slices );
}

// save slices
jPost.saveSlices = function() {
    if( jPost.slices.length > 0 ) {
  	    var json = JSON.stringify( jPost.slices );
  	    localStorage.setItem( 'jPOST-slices', json );
    }
    else {
        localStorage.removeItem( 'jPOST-slices' );
    }
    console.log( jPost.slices );
}

// create new slice
jPost.createNewSlice = function() {
    jPost.tmp.slice = null;
    jPost.tmp.datasets = null;

    jPost.openSliceDialog();
}

// comapre
jPost.compareSlices = function() {
    var name1 = $( '#select-comparison-slice1' ).val();
    var name2 = $( '#select-comparison-slice2' ).val();

    var slice1 = jPost.getSlice( name1 );
    var slice2 = jPost.getSlice( name2 );

    if( slice1 === null || slice2 === null ) {
        return;
    }

    var datasets1 = encodeURI( slice1.datasets.join( ' ' ) );
    var datasets2 = encodeURI( slice2.datasets.join( ' ' ) );

    var url = 'stanza?stanza=slice_comp_stat&dataset1='
            + datasets1 + '&dataset2=' + datasets2;
    $( '#comparison-table' ).load( url );

    url = 'stanza?stanza=group_comp&method=sc&valid=eb&dataset1='
            + datasets1 + '&dataset2=' + datasets2;
    $( '#comparison-chart' ).load( url );
}

// export Slices
jPost.exportSlice = function( name ) {
  var array = [];
	var filename = '';

	if( name == null || name == '' ) {
		  filename = 'all.json';
		  array = jPost.slices;
	}
	else {
		  filename = name + '.json';
		  var slice = jPost.getSlice( name );
		  if( slice != null ) {
			    array.push( slice );
      }
	}

	if( array.length == 0 ) {
		  alert( 'There are no slices.' );
	}
	else {
		  var json = JSON.stringify( array );
		  var file = new File(
				  [ json ],
				  filename,
				  { type: 'text/plain;charset=utf-8' }
      );
		  saveAs( file );
	}
}

// import slices
jPost.importSlices = function() {
	 $( '#upload_slices' ).click();
}
