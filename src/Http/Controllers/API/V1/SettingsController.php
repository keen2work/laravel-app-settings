<?php

namespace EMedia\AppSettings\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use EMedia\Api\Docs\APICall;
use EMedia\Api\Docs\Param;
use EMedia\AppSettings\Entities\Settings\Setting;
use EMedia\AppSettings\Entities\Settings\SettingsRepository;

class SettingsController extends Controller
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
     * Returns the list of app settings
     */
    public function index()
    {
        document(function () {
            return (new APICall)->setName('Get Settings')
                ->setDescription('Returns all app settings. Each setting value is identified by the respective key.')
                ->setConsumes([APICall::CONSUME_JSON])
                ->noDefaultHeaders()
                ->setHeaders([
                    (new Param('Accept', 'String', '`application/json`'))->setDefaultValue('application/json'),
                    (new Param('x-api-key', 'String', 'API Key'))->setDefaultValue('123-123-123-123'),
                ])
                ->setSuccessPaginatedObject(Setting::class)
                ->setSuccessExample('{
    "payload": {
        "settings": [
            {
                "id": 1,
                "created_at": "2020-06-17T11:05:27.000000Z",
                "updated_at": "2020-06-17T11:05:27.000000Z",
                "key": "ABOUT_US",
                "value": null
            },
            {
                "id": 2,
                "created_at": "2020-06-17T11:05:27.000000Z",
                "updated_at": "2020-06-17T11:05:27.000000Z",
                "key": "PRIVACY_POLICY",
                "value": null
            },
            {
                "id": 3,
                "created_at": "2020-06-17T11:05:27.000000Z",
                "updated_at": "2020-06-17T11:05:27.000000Z",
                "key": "TERMS_AND_CONDITIONS",
                "value": null
            }
        ]
    },
    "message": "",
    "result": true
}');
        });

        $settings = $this->settingsRepo->all();

        return response()->apiSuccess($settings);
    }

    /**
     * Returns the app setting of the requested key
     */
    public function show($key)
    {
        document(function () {
            return (new APICall)->setName('Get Setting')
                ->setDescription('Returns the value of a single app setting requested by key.')
                ->setConsumes([APICall::CONSUME_JSON])
                ->setParams([
                    (new Param('key', 'String', 'Key of the setting', 'path')),
                ])
                ->setSuccessExample('{
    "payload": {
        "id": 1,
        "created_at": "2020-06-17T11:05:27.000000Z",
        "updated_at": "2020-06-17T11:05:27.000000Z",
        "key": "ABOUT_US",
        "value": null
    },
    "message": "",
    "result": true
}');
        });

        $setting = Setting::where('setting_key', $key)->first();

        if ($setting) {
            return response()->apiSuccess($setting);
        }

        return response()->apiError('Requested setting could not be found.');
    }
}
