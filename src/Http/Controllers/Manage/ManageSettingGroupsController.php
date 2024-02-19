<?php


namespace EMedia\AppSettings\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use ElegantMedia\OxygenFoundation\Http\Traits\Web\CanCRUD;
use EMedia\AppSettings\Entities\SettingGroups\SettingGroupsRepository;
use EMedia\Formation\Builder\Formation;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ManageSettingGroupsController extends Controller
{

	use CanCRUD {
		update as protected parentUpdate;
	}

	public function __construct(SettingGroupsRepository $repo)
	{
		$this->repo = $repo;

		$this->resourceEntityName = 'Setting Groups';

		$this->viewsVendorName = 'app-settings';

		$this->resourcePrefix = 'manage.setting-groups';

		$this->isDestroyAllowed = true;
	}


	public function getCreateViewName()
	{
		return 'oxygen::defaults.formation-form';
	}

	public function getEditViewName()
	{
		return 'oxygen::defaults.formation-form';
	}

	public function getRouteToRedirectToAfterStore()
	{
		return 'manage.setting-groups.index';
	}

	public function getRouteToRedirectToAfterUpdate()
	{
		return 'manage.setting-groups.index';
	}

	public function create()
	{
		$data = [
			'pageTitle' => $this->getCreatePageTitle(),
			'entity' => $this->getModel(),
			'form' => (new Formation($this->getModel())),
		];

		$viewName = $this->getCreateViewName();

		return view($viewName, $data);
	}

	/**
	 *
	 * Edit the resource
	 *
	 * @param $id
	 *
	 * @return Factory|View
	 */
	public function edit($id)
	{
		$entity = $this->repo->find($id);

		$data = [
			'pageTitle' => $this->getEditPageTitle($entity),
			'entity' => $entity,
			'form' => (new Formation($entity)),
		];

		$viewName = $this->getEditViewName();

		return view($viewName, $data);
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
		$entity = $this->repo->findOrFail($id);

		if (!$entity->is_name_editable) {
			return back()->with('error', 'This group is locked and cannot be edited.');
		}

		return $this->parentUpdate($request, $id);
	}
}
