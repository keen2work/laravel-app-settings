<?php

// Start AppSettings Routes
Route::group(['prefix' => 'v1', 'namespace' => '\EMedia\AppSettings\Http\Controllers\API\V1'], function() {
	Route::get('/settings', 'SettingsController@index')->name('settings.index');
	Route::get('/settings/{key}', 'SettingsController@show')->name('settings.show');
});
// End AppSettings Routes
