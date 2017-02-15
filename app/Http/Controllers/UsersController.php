<?php

namespace App\Http\Controllers;

use App\DataTables\UsersDataTable;
use App\Http\Requests\Users\CreateRequest as UsersCreateRequest;
use App\Http\Requests\Users\UpdateRequest as UsersUpdateRequest;
use App\Models\Role;
use App\Repositories\UsersRepository;
use Illuminate\Http\Request;

class UsersController extends BaseController
{
    /* @var UsersRepository */
    protected $repository;

    public function __construct(UsersRepository $repository)
    {
        $this->repository = $repository;
    }

    private function getRoles()
    {
        return collect(['' => 'Selecione'] + Role::pluck('name', 'id')->all());
    }

    public function index(UsersDataTable $dataTable)
    {
        $this->authorize('users.index');

        return $dataTable->render('users.index');
    }

    public function create()
    {
        $this->authorize('users.create');

        $roles = $this->getRoles();

        return view('users.create', compact('users', 'roles'));
    }

    public function store(UsersCreateRequest $request)
    {
        $this->authorize('users.create');

        $this->repository->create($request->all());

        \Flash::success(trans('messages.created_success', ['module' => trans('modules.users.user')]));

        return redirect(route('users.index'));
    }

    public function edit($id)
    {
        $this->authorize('users.edit');

        $user = $this->repository->find($id);
        $roles = $this->getRoles();

        return view('users.edit', compact('user', 'roles'));
    }

    public function show($id)
    {
        $this->authorize('users.show');

        $user = $this->repository->find($id);

        return view('users.show', compact('user'));
    }

    public function update($id, UsersUpdateRequest $request)
    {
        $this->authorize('users.edit');

        $this->repository->update($request->all(), $id);

        \Flash::success(trans('messages.updated_success', ['module' => trans('modules.users.user')]));

        return redirect(route('users.index'));
    }

    public function destroy($id)
    {
        $this->authorize('users.destroy');

        $this->repository->delete($id);

        \Flash::success(trans('messages.deleted_success', ['module' => trans('modules.users.user')]));

        return redirect(route('users.index'));
    }
}
