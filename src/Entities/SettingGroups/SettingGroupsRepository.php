<?php


namespace EMedia\AppSettings\Entities\SettingGroups;


class SettingGroupsRepository extends \ElegantMedia\OxygenFoundation\Entities\OxygenRepository
{

	public function __construct(SettingGroup $model)
	{
		parent::__construct($model);
	}

}
