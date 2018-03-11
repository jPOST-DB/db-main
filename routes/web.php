<?php

Route::get( '/', 'SearchController@index' );
Route::get( '/index', 'SearchController@index' );
Route::get( '/search', 'SearchController@index' );
Route::get( '/stanza', 'StanzaController@index' );
Route::get( '/sparqlist/{method}', 'SparqlistController@index' );
Route::post( '/datasets', 'DatasetsController@index' );
Route::get( '/datasets', 'DatasetsController@index' );
Route::post( '/proteins', 'ProteinsController@index' );
Route::get( '/proteins', 'ProteinsController@index' );
Route::post( '/dataset', 'DatasetController@index' );
Route::get( '/dataset', 'DatasetController@index' );
Route::post( '/peptides', 'PeptidesController@index' );
Route::get( '/peptides', 'PeptidesController@index' );
Route::post( '/protein', 'ProteinController@index' );
Route::get( '/protein', 'ProteinController@index' );
Route::post( '/psms', 'PsmsController@index' );
Route::get( '/psms', 'PsmsController@index' );
Route::post( '/peptide', 'PeptideController@index' );
Route::get( '/peptide', 'PeptideController@index' );
Route::post( '/slices', 'SliceController@index' );
Route::get( '/slices', 'SliceController@index' );
Route::post( '/compare', 'CompareController@index' );
Route::get( '/compare', 'CompareController@index' );
Route::get( '/help', 'HelpController@index' );

?>
