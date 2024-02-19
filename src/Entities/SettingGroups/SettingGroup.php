<?php


namespace EMedia\AppSettings\Entities\SettingGroups;


use ElegantMedia\SimpleRepository\Search\Eloquent\SearchableLike;
use EMedia\AppSettings\Entities\Settings\Setting;
use EMedia\Formation\Entities\GeneratesFields;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class SettingGroup extends Model
{

	use GeneratesFields;
	use SearchableLike;
	use HasSlug;

	protected $searchable = [
		'name',
	];

	protected $fillable = [
		'name',
		'description',
		'sort_order',
	];

	protected $editable = [
		'name',
		'sort_order',
	];

	public function getSlugOptions(): SlugOptions
	{
		return SlugOptions::create()->generateSlugsFrom('name')->saveSlugsTo('slug');
	}

	public function getCreateRules()
	{
		return [
			'name' => 'required|min:2',
		];
	}

	public function getUpdateRules()
	{
		return [
			'name' => 'required|min:2',
		];
	}

	public function settings()
	{
		return $this->hasMany(Setting::class);
	}

}
