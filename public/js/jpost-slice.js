jPost.slices = [];
jPost.tmp = {
    datasets: null,
    slice: null
};
jPost.slice = null;

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

// slice
jPost.setSlice = function() {
    var name = $( '#slice-name' ).val();
    var slice = jPost.getSlice( name );

    jPost.sets.datasets = [];
    if( slice !== null ) {
        slice.datasets.forEach(
            function( dataset ) {
                jPost.sets.datasets.push( dataset );
            }
        );

        $( '#slice-name' ).val( slice.name );

        if( jPost.sets.datasets.length === 0 ) {
            jPost.sets.datasets.push( 'DS99_999999' );
        }
    }

    jPost.slice = slice;
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

	  jPost.tmp.datasets = array;

    jPost.updateSliceSelection();
	  $( '#dialog-slice-selection' ).modal( 'show' );
	  if( jPost.slices.length == 0 ) {
		    jPost.openSliceDialog();
	  }
}

// update slice selection
jPost.updateSliceSelection = function() {
    var slice = jPost.slice;
    if( slice === null && jPost.slices.length > 0 ) {
        slice = jPost.slices[ 0 ];
    }

    if( slice === null ) {
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

    localStorage.removeItem( 'jPOST-compare' );

    if( slice1 === null || slice2 === null ) {
        return;
    }

    name1 = encodeURI( name1 );
    name2 = encodeURI( name2 );

    var datasets1 = encodeURI( slice1.datasets.join( ' ' ) );
    var datasets2 = encodeURI( slice2.datasets.join( ' ' ) );

    var url = 'stanza?stanza=slice_comparison&dataset1='
            + datasets1 + '&dataset2=' + datasets2
            + '&slice1=' + name1 + '&slice2=' + name2;
    $( '#comparison-chart' ).load( url );
    jPost.saveCompareSlices();

    var json = JSON.stringify( [ slice1.name, slice2.name ] );
  	localStorage.setItem( 'jPOST-compare', json );        
}

// export Slices
jPost.exportSlice = function() {
    var slice = jPost.slice;

	  if( slice == null ) {
        return;
    }

    var array = [ slice ];
    var name = slice.name;
	  var filename = name + '.json';

    jPost.exportSlices( filename, array );
}

// export all slices
jPost.exportAllSlices = function() {
    var filename = 'all.json';
    var array = jPost.slices;

    jPost.exportSlices( filename, array );
}

// save slices
jPost.exportSlices = function( filename, array ) {
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

// open rename dialog
jPost.openRenameDialog = function() {
    var slice = jPost.slice;
    if( slice === null ) {
        return;
    }
    $( '#dialog-rename-slice-old-name' ).val( slice.name );
    $( '#dialog-rename-slice-new-name' ).val( slice.name );
    $( '#dialog-rename-slice' ).modal( 'show' );
}

// rename slice
jPost.renameSlice = function() {
    var oldName = $( '#dialog-rename-slice-old-name' ).val();
	  var newName = $( '#dialog-rename-slice-new-name' ).val();

	  if( oldName == newName ) {
		    return;
	  }

	  var slice = jPost.getSlice( newName );
	  if( slice != null ) {
		    alert( 'Slice "' + newName + '" already exists.' );
		    return;
	  }

	  slice = jPost.getSlice( oldName );
	  slice.name = newName;
    jPost.saveSlices();

    location.reload();
}


// delete slice
jPost.deleteSlice = function() {
    var slice = jPost.slice;
    if( slice === null ) {
        return;
    }
    var name = slice.name;

    if( confirm( 'Are you sure to delete the slice?' ) ) {
        var index = -1;
        for( var i = 0; i < jPost.slices.length; i++ ) {
            if( jPost.slices[ i ].name == name ) {
                index = i;
            }
        }

        if( index >= 0 ) {
            jPost.slices.splice( index, 1 );
        }

        jPost.saveSlices();
        name = $( '#slice-name' ).val();
        if( name === '' ) {
              location.reload();
        }
        else {
            window.location.href = 'slices'
        }
    }
}

// select slices
jPost.selectSlice = function( name ) {
    if( $( '#slice-naem'  ).val() === name ) {
      return;
    }
    $( '#slice-name' ).val( name );
    jPost.setSlice();
    jPost.updateTables();
    $( '.slice-title-name' ).html( name );

    jPost.loadStanzas();
}

// remove datasets
jPost.removeDatasets = function() {
    var array = jPost.getCheckedArray( 'check_dataset' );
    if( array.length == 0 ) {
        alert( "No datasets are not selected. Please check one or more datasets." );
        return;
	  }

    var slice = jPost.slice;
    if( slice === null ) {
        return;
    }

    slice.base = Object.assign( {}, slice );
    slice.operation = 'remove';
    slice.parameters = jPost.getSearchParameters();
    slice.date = jPost.getDate();
    slice.datasets = [];

    slice.base.datasets.forEach(
        function( dataset ) {
            var index = array.indexOf( dataset );
            if( index < 0 ) {
                slice.datasets.push( dataset );
            }
        }
    );

    jPost.saveSlices();
    jPost.selectSlice( slice.name );
}
