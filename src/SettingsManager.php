<?php


namespace EMedia\AppSettings;


use EMedia\AppSettings\Entities\Settings\Setting;
use EMedia\AppSettings\Entities\Settings\SettingsRepository;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class SettingsManager
{

	/**
	 * @var SettingsRepository
	 */
	private $settingsRepo;

	public function __construct(SettingsRepository $settingsRepo)
	{
		$this->settingsRepo = $settingsRepo;
	}

	/**
	 *
	 * Set a setting
	 *
	 * @param $key
	 * @param $value
	 * @param null $dataType
	 * @param null $description
	 *
	 * @return \Illuminate\Database\Eloquent\Model|mixed
	 */
	public function set($key, $value = null, $dataType = null, $description = null)
	{
		$data = [
			'setting_key' => $key,
			'setting_value' => $value,
			'setting_data_type' => $dataType,
			'description' => $description,
		];

		if ($dataType) {
			if ($dataType === Setting::DATA_TYPE_JSON) {
				$data['setting_value'] = json_encode($value);
			}

			if (!in_array($dataType, $this->validDataTypes(), true)) {
				throw new InvalidArgumentException("The data type `{$dataType}` is invalid");
			}
		}

		return $this->setByArray($data);
	}

	/**
	 *
	 * Update by ID
	 *
	 * @param $id
	 * @param $data
	 *
	 * @return mixed
	 */
	public function updateById($id, array $data)
	{
		$this->validate($data, false);
		$setting = $this->settingsRepo->find($id);

		if (!$setting) {
			throw new InvalidArgumentException("Invalid setting ID");
		}

		// if the key is not editable, ignore it
		if (!$setting->is_key_editable) {
			if (isset($data['setting_key'])) unset($data['setting_key']);
		}

		// if value is not editable, ignore both key and value
		if (!$setting->is_value_editable) {
			if (isset($data['setting_key'])) unset($data['setting_key']);
			if (isset($data['setting_value'])) unset($data['setting_value']);
		}

		return $this->settingsRepo->update($setting, $data);
	}

	/**
	 *
	 * Set or update an existing setting
	 *
	 * @param      $key
	 * @param      $value
	 * @param null $dataType
	 * @param null $description
	 *
	 * @return mixed
	 */
	public function setOrUpdate(string $key, string $value = null, $dataType = null, string $description = null)
	{
		/** @var Setting $existingSetting */
		$existingSetting = Setting::where('setting_key', $key)->first();

		if ($existingSetting) {
			return $this->settingsRepo->update($existingSetting, [
				'setting_key' => $key,
				'setting_value' => $value,
				'setting_data_type' => $dataType,
				'description' => $description,
			]);
		}

		return $this->set($key, $value, $dataType, $description);
	}

	/**
	 *
	 * Set a new setting by an array
	 *
	 * @param $data
	 *
	 * @return \Illuminate\Database\Eloquent\Model|mixed
	 */
	public function setByArray($data)
	{
		$this->validate($data);

		return $this->settingsRepo->create($data);
	}

	/**
	 *
	 * Retrieve a setting from the database
	 *
	 * @param $key
	 *
	 * @param string $default
	 *
	 * @return string
	 */
	public function get($key, $default = '')
	{
		$setting = Setting::where('setting_key', $key)->first();

		if ($setting) {
			if (isset($setting->setting_data_type) && $setting->setting_data_type !== null) {
				return $this->castToType($setting->setting_value, $setting->setting_data_type);
			}
			return $setting->setting_value;
		}

		return $default;
	}

	/**
	 *
	 * Remove a setting if it exists
	 *
	 * @param $key
	 *
	 * @return void
	 */
	public function forget($key): void
	{
		$setting = Setting::where('setting_key', $key)->first();

		// only delete if they're editable
		if ($setting && $setting->is_key_editable) {
			$setting->delete();
		}
	}

	protected function validate($data, $isNewRecord = true): void
	{
		$rules = [
			'setting_key'  => 'required|unique:settings,setting_key',
			'setting_value' => 'present',
		];

		if (!$isNewRecord) {
			// if this is not a new record, it won't be unique in the DB
			$rules['setting_key'] = 'required';
		}

		$validator = Validator::make($data, $rules);

		if ($validator->fails())
		{
			$message = implode(' ', $validator->messages()->all());
			throw new InvalidArgumentException($message);
		}
	}

	/**
	 *
	 * Explicit type casting
	 *
	 * @param $value
	 * @param $type
	 *
	 * @return bool|float|int|mixed
	 */
	protected function castToType($value, $type)
	{
		switch ($type) {
			case 'int':
			case 'integer':
				return (integer) $value;
			case 'bool':
			case 'boolean':
				return (boolean) $value;
			case 'float':
			case 'double':
			case 'real':
				return (float) $value;
			case Setting::DATA_TYPE_JSON:
				return json_decode($value, true);
			default:
				return $value;
		}
	}

	private function validDataTypes()
	{
		return [
			'int', 'integer',
			'bool', 'boolean',
			'float', 'double', 'real',
			'string',
			Setting::DATA_TYPE_JSON,
		];
	}

}
