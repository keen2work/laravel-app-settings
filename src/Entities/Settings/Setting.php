<?php


namespace EMedia\AppSettings\Entities\Settings;


use ElegantMedia\SimpleRepository\Search\Eloquent\SearchableLike;
use EMedia\AppSettings\Entities\SettingGroups\SettingGroup;
use EMedia\Formation\Entities\GeneratesFields;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Setting extends Model
{

	public const DATA_TYPE_JSON    = 'json';
	public const DATA_TYPE_TEXT    = 'text';
	public const DATA_TYPE_STRING  = 'string';
	public const DATA_TYPE_BOOLEAN = 'bool';

	use GeneratesFields;
	use SearchableLike;

	public function getSearchableFields(): array
	{
		return [
			'setting_key',
			'setting_value',
			'description',
		];
	}

	protected $fillable = [
		'setting_key',
		'setting_value',
		'setting_data_type',
		'description',
		'setting_group_id',
	];

	protected $hidden = [
		'setting_key',
		'setting_value',
		'setting_data_type',
		'description',
		'is_key_editable',
		'is_value_editable',
	];

	protected $appends = [
		'key',
		'value',
	];

	protected $visible = [
		'id',
		'key',
		'value',
		'created_at',
		'updated_at',
	];

	protected $editable = [
		[
			'name' => 'setting_key',
			'display_name' => 'Key',
		],
		[
			'name' => 'setting_value',
			'display_name' => 'Value',
		],
		[
			'name' => 'description',
		],
		[
			'name' => 'setting_data_type',
			'display_name' => 'Data Type',
			'type' => 'select',
			'options' => [
				self::DATA_TYPE_STRING => 'String',
				self::DATA_TYPE_TEXT => 'Text',
				self::DATA_TYPE_BOOLEAN => 'True/False',
			]
		],
		[
			'name' => 'setting_group_id',
			'display_name' => 'Setting Group',
			'options_entity' => SettingGroup::class,
			'type' => 'select',
		]
	];

	public function getCreateRules()
	{
		return [
			'setting_key' => 'required|unique:settings,setting_key',
		];
	}

	public function getUpdateRules($id = null)
	{
		return [
			'setting_key' => [
				'required',
				Rule::unique('settings', 'setting_key')->ignore($id),
			],
		];
	}

	public function getIsKeyEditableAttribute($value)
	{
		if (isset($this->attributes['is_key_editable'])) {
			return $this->attributes['is_key_editable'];
		}

		return true;
	}

	public function getExtraApiFields()
    {
        return [
            'key',
            'value',
        ];
    }

	/**
	 * Returns the setting key
	 */
	public function getKeyAttribute()
	{
		return $this->setting_key;
	}

	/**
	 * Returns the setting value
	 */
	public function getValueAttribute()
	{
		return $this->setting_value;
	}

	public function group()
	{
		return $this->belongsTo(SettingGroup::class, 'setting_group_id');
	}
}
