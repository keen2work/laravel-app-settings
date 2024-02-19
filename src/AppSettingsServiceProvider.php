<?php


namespace EMedia\AppSettings;


use ElegantMedia\OxygenFoundation\Facades\Navigator;
use ElegantMedia\OxygenFoundation\Navigation\NavItem;
use EMedia\AppSettings\Commands\OxygenAppSettingsInstallCommand;
use EMedia\AppSettings\Console\Commands\AppSettingsPackageSetupCommand;
use Illuminate\Support\ServiceProvider;

class AppSettingsServiceProvider extends ServiceProvider
{

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		if (app()->environment(['local', 'testing'])) {
			$this->commands(OxygenAppSettingsInstallCommand::class);
		}

		$this->app->singleton(SettingsManager::class);
	}

	public function boot()
	{
		$this->publishes([
			__DIR__ . '/../publish' => base_path(),
		], 'oxygen::auto-publish');

		$this->loadViewsFrom(__DIR__ . '/../resources/views', 'app-settings');

		$this->publishes([
			__DIR__ . '/../resources/views' => base_path('resources/views/vendor/app-settings'),
		], 'views');

		$this->setupNavItem();
	}

	protected function setupNavItem()
	{
		// register the menu items
		$navItem = new NavItem('Settings');
		$navItem->setResource('manage.settings.index')
			->setIconClass('fas fa-cogs');

		Navigator::addItem($navItem, 'sidebar.manage');
	}

}
