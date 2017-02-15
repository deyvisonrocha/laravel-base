<?php

namespace App\Http\Controllers;

use App\DataTables\RolesDataTable;
use App\Http\Requests\Roles\CreateRequest as RolesCreateRequest;
use App\Http\Requests\Roles\UpdateRequest as RolesUpdateRequest;
use App\Repositories\RolesRepository;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class RolesController extends BaseController
{
    /* @var RolesRepository */
    protected $repository;

    public function __construct(RolesRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getPermissions()
    {
        $permissions = [];
        $permission_modules = Permission::select('module')->groupBy('module')->orderBy('module')->get();

        foreach ($permission_modules as $module) {
            $permissions[$module->module] = Permission::select('id', 'label')->where('module' , $module->module)->get()->toArray();
        }

        return $permissions;
    }

    public function index(RolesDataTable $dataTable)
    {
        $this->authorize('roles.index');

        return $dataTable->render('roles.index');
    }

    public function create()
    {
        $this->authorize('roles.create');

        $permissions = $this->getPermissions();

        return view('roles.create', compact('permissions'));
    }

    public function store(RolesCreateRequest $request)
    {
        $this->authorize('roles.create');

        $this->repository->create($request->all());

        \Flash::success(trans('messages.created_success', ['module' => trans('modules.roles.role')]));

        return redirect(route('roles.index'));
    }

    public function edit($id)
    {
        $this->authorize('roles.edit');

        $role = $this->repository->find($id);
        $permissions = $this->getPermissions();

        $permissions_selected = $role->permissions->pluck('id')->toArray();
        $permissions = $this->getPermissions();

        return view('roles.edit', compact('role', 'permissions', 'permissions_selected'));
    }

    public function show($id)
    {
        $this->authorize('roles.show');

        $role = $this->repository->find($id);

        return view('roles.show', compact('role'));
    }

    public function update($id, RolesUpdateRequest $request)
    {
        $this->authorize('roles.edit');

        $this->repository->update($request->all(), $id);

        \Flash::success(trans('messages.updated_success', ['module' => trans('modules.roles.role')]));

        return redirect(route('roles.index'));
    }

    public function destroy($id)
    {
        $this->authorize('roles.destroy');

        $this->repository->delete($id);

        \Flash::success(trans('messages.deleted_success', ['module' => trans('modules.roles.role')]));

        return redirect(route('roles.index'));
    }
}
