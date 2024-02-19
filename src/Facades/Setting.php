<?php
namespace EMedia\AppSettings\Facades;

use EMedia\AppSettings\SettingsManager;

/**
 * @method static set($key, $value = null, $dataType = null, $description = null)
 * @method static updateById($id, array $data);
 * @method static setOrUpdate(string $key, string $value = null, $dataType = null, $description = null);
 * @method static Setting setByArray(array $data);
 * @method static get($key, $default = '')
 * @method static forget($key) Remove a setting if it exists
 * @method static Navigator addItem(NavItem $item, $navBarName = 'default');
 * @method static Collection items($navBarName = 'default');
 */
class Setting extends \Illuminate\Support\Facades\Facade
{

	protected static function getFacadeAccessor()
	{
		return SettingsManager::class;
	}

}
