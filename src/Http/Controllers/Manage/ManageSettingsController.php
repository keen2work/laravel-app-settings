<?php

namespace EMedia\AppSettings\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use ElegantMedia\OxygenFoundation\Http\Traits\Web\CanCRUD;
use EMedia\AppSettings\Entities\Settings\SettingsRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use ValidationException;

class ManageSettingsController extends Controller
{

	use CanCRUD;

	public function __construct(SettingsRepository $repo)
	{
		$this->repo = $repo;

		$this->resourceEntityName = 'Settings';

		$this->viewsVendorName = 'app-settings';

		$this->resourcePrefix = 'manage.settings';

		$this->isDestroyAllowed = true;
	}

	public function getIndexRouteName()
	{
		return 'manage.settings.index';
	}

	protected function getIndexFilter(): \ElegantMedia\SimpleRepository\Search\Filterable
	{
		$filter = $this->repo->newSearchFilter(true);

		$filter->with(['group']);

		return $filter;
	}

	/**
	 *
	 * Handle update/PUT request for the controller
	 *
	 * @param Request $request
	 * @param         $id
	 *
	 * @return RedirectResponse
	 */
	public function update(Request $request, $id): RedirectResponse
	{
		$entity = $this->repo->find($id);

		$rules = null;
		if (method_exists($this->getModel(), 'getUpdateRules')) {
			$rules = $this->getModel()->getUpdateRules($entity->id);
		}

		$messages = null;
		if (method_exists($this->getModel(), 'getUpdateValidationMessages')) {
			$messages = $this->getModel()->getUpdateValidationMessages();
		}

		$entity = $this->storeOrUpdateRequest($request, $id, $rules, $messages);

		return redirect()->route($this->getRouteToRedirectToAfterUpdate());
	}

}
