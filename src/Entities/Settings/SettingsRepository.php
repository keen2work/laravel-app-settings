<?php


namespace EMedia\AppSettings\Entities\Settings;


use ElegantMedia\OxygenFoundation\Entities\OxygenRepository;

class SettingsRepository extends OxygenRepository
{

	public function __construct(Setting $model)
	{
		parent::__construct($model);
	}

}
