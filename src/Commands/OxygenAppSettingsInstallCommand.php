<?php

namespace EMedia\AppSettings\Commands;

use ElegantMedia\OxygenFoundation\Console\Commands\ExtensionInstallCommand;
use EMedia\AppSettings\AppSettingsServiceProvider;

class OxygenAppSettingsInstallCommand extends ExtensionInstallCommand
{

	protected $signature = 'oxygen:app-settings:install';

	protected $description = 'Setup the App Settings Extension';

	public function getExtensionServiceProvider(): string
	{
		return AppSettingsServiceProvider::class;
	}

	public function getExtensionDisplayName(): string
	{
		return 'App Settings';
	}
}
