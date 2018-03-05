<?php

Route::get( '/', 'SearchController@index' );
Route::get( '/index', 'SearchController@index' );
Route::get( '/search', 'SearchController@index' );
Route::get( '/stanza', 'StanzaController@index' );
Route::get( '/sparqlist/{method}', 'SparqlistController@index' );
Route::get( '/datasets', 'DatasetsController@index' );
Route::get( '/proteins', 'ProteinsController@index' );
Route::get( '/dataset', 'DatasetController@index' );
Route::get( '/peptides', 'PeptidesController@index' );
Route::get( '/protein', 'ProteinController@index' );
Route::get( '/psms', 'PsmsController@index' );
Route::get( '/peptide', 'PeptideController@index' );
Route::get( '/slices', 'SliceController@index' );
Route::get( '/compare', 'CompareController@index' );
Route::get( '/help', 'HelpController@index' );

?>
