<?php

if (!function_exists('setting'))
{
	/**
	 *
	 * Helper function for the setting facade
	 *
	 * @param        $key
	 * @param string $default
	 *
	 * @return mixed
	 */
	function setting($key, $default = '')
	{
		return \EMedia\AppSettings\Facades\Setting::get($key, $default);
	}
}


if (!function_exists('setting_set'))
{
	/**
	 *
	 * Helper function to set a setting
	 *
	 * @param      $key
	 * @param null $value
	 * @param null $dataType
	 * @param null $description
	 *
	 * @return mixed
	 */
	function setting_set($key, $value = null, $dataType = null, $description = null)
	{
		return \EMedia\AppSettings\Facades\Setting::setOrUpdate($key, $value, $dataType, $description);
	}
}

if (!function_exists('setting_forget'))
{
	/**
	 *
	 * Delete a setting
	 *
	 * @param      $key
	 */
	function setting_forget($key)
	{
		\EMedia\AppSettings\Facades\Setting::forget($key);
	}
}
