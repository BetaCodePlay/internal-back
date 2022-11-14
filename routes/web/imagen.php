<?php
/**
* Imagem routes
*/
Route::group(['prefix' => 'imagen', 'middleware' => []], function () {

    // Show imagen list
    Route::get('', [
        'as' => 'imagen.index',
        'uses' => 'ImagenController@index'
    ]);

    // Update imagen
    Route::post('imagen', [
        'as' => 'imagen.optimize',
        'uses' => 'ImagenController@imagenUpload'
    ]);
});
