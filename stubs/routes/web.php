<?php

// Start AppSettings Routes
Route::group(['prefix' => '/manage/settings', 'middleware' => ['auth', 'auth.acl:roles[super-admins|admins|developers]'], 'as' => 'manage.'], function()
{
	// Settings
	Route::group([
		'namespace' => '\EMedia\AppSettings\Http\Controllers\Manage',
	], function () {
		Route::get('/', 'ManageSettingsController@index')->name('settings.index');
		Route::get('/new', 'ManageSettingsController@create')->name('settings.create');
		Route::get('/{id}/edit', 'ManageSettingsController@edit')->name('settings.edit');

		Route::post('/', 'ManageSettingsController@store')->name('settings.store');
		Route::put('/{id}', 'ManageSettingsController@update')->name('settings.update');
		Route::delete('/{id}', 'ManageSettingsController@destroy')->name('settings.destroy');
	});

	// Groups
	Route::group([
		'prefix' => 'groups',
		'namespace' => '\EMedia\AppSettings\Http\Controllers\Manage',
	], function () {
		Route::get('/', 'ManageSettingGroupsController@index')->name('setting-groups.index');
		Route::get('/new', 'ManageSettingGroupsController@create')->name('setting-groups.create');
		Route::get('/{id}/edit', 'ManageSettingGroupsController@edit')->name('setting-groups.edit');

		Route::post('/', 'ManageSettingGroupsController@store')->name('setting-groups.store');
		Route::put('/{id}', 'ManageSettingGroupsController@update')->name('setting-groups.update');
		Route::delete('/{id}', 'ManageSettingGroupsController@destroy')->name('setting-groups.destroy');
	});
});
// End AppSettings Routes
