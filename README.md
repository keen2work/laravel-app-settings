# Laravel App Settings

Save app settings to database and retrieve them.

## Version Compatibility

| Laravel Version | Package Version | Branch       |
|-----------------|-----------------|--------------|
| v10             | 6.x             | 6.x          |
| v9              | 5.x             | 5.x          |
| v8              | 4.x             | 4.x          |
| v7              | 3.x             | version/v3.x |
| v6              | 2.x             |              |
| v5.8            | 1.1.1           |              |

See [change log for change history](CHANGELOG.md) and compatibility with past versions.

### Installation Instructions

Add the repository to `composer.json`
```
"repositories": [
	{
	    "type":"vcs",
	    "url":"git@bitbucket.org:elegantmedia/laravel-app-settings.git"
	}
]
```

```
composer require emedia/app-settings
```

Install the package.

```
php artisan oxygen:app-settings:install
```

Then migrate to create the tables and seed the database.

``` bash
php artisan migrate
php artisan db:seed
```

## Optional Customisation Steps

```
// Seed the database
php artisan db:seed --class="Database\Seeders\OxygenExtensions\AutoSeed\AppSettingsTableSeeder"

// Publish views
php artisan vendor:publish --provider="EMedia\AppSettings\AppSettingsServiceProvider" --tag=views --force
```

## Usage

```
// Set a setting (this will call setOrUpdate)
setting_set('mySetting', '3445');

// or use the Facade
{{ Setting::setOrUpdate('mySetting', '3445') }}

// Retrieving a setting
setting('mySetting', 'default');

// Delete a setting
setting_forget('mySetting');

// or use the Facade
{{ Setting::get('mySetting') }}
{{ Setting::get('mySetting', $default) }}

// Update a setting
{{ Setting::update('mySetting', '3445') }}

// Set a setting (this will throw an exception if setting exists)
{{ Setting::set('mySetting', '3445') }}

// Set or Update a Setting
{{ Setting::setOrUpdate('mySetting', '3445') }}

// Set multiple settings
{{ Setting::setByArray([ 'setting_key' => 'max_num_users', 'setting_value' => 100, 'setting_data_type' => 'int', 'description' => 'Maximum number of allowed users.' ]) }}

// Delete a setting
{{ Setting::forget('mySetting') }}
```

## More Info

#### Settings Manager

The helper methods call the `Setting` facade which itself is an instance of the `SettingsManager` class. The manager class is responsible for validation, and casting values before handing them to be stored by the repository.

#### Settings Repository

The `SettingsRepository` is in charge of retrieving, and storing values.  

#### Fields

Settings are stored in a `settings` table in the database. Each setting contains the following fields:

| Key | Type | value | Description |
| --- | ---- | ----- | ----------- |
| `setting_key` | string | unique | Key 
| `setting_data_type` | string | optional with allowed value:  int, integer, bool, boolean, float, double, real, string, json | Cast the value into the expected type
| `setting_value` | text | optional | Value
| `setting_description` | string | optional | Description

In addition to the fields above each setting also has the following control fields:

| Key | Type | value | Description |
| --- | ---- | ----- | ----------- |
| `is_key_editable` | boolean | default: true | Update key allowed 
| `is_value_editable` | boolean | default: true | Update value allowed

Control fields can only be set by the `Setting::setByArray` method. All other `set` methods will use the defaults.

#### Routes

You may also make http requests directly to update settings

| Method | Route  | Fields | Description |
| ------ | ------ | ------ | ----------- |
| POST   | /manage/settings | setting_key, setting_value, setting_data_type, description | Save a new setting | 
| PUT    | /manage/settings/{id} | setting_key, setting_value, setting_data_type, description | Update a setting at given id |
| DELETE |  /manage/settings/{id} | - | Remove a setting 

## Contributing

- Found a bug? Report as an issue and if you can, submit a pull request.
- Please see [CONTRIBUTING](CONTRIBUTING.md) and for details.

Copyright (c) 2020 Elegant Media.
