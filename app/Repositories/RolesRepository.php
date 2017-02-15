<?php

namespace App\Repositories;

use App\Exceptions\ModelHasRelationsException;
use App\Models\Role;

class RolesRepository extends BaseRepository
{
    public function model()
    {
        return Role::class;
    }

    public function create(array $attributes)
    {
        $role = parent::create($attributes);

        $permissions = $attributes['permissions'];

        $role->permissions()->sync($permissions);

        return $role;
    }

    public function update(array $attributes, $id)
    {
        $role = parent::update($attributes, $id);

        $permissions = $attributes['permissions'];

        $role->permissions()->sync($permissions);

        foreach ($role->users as $user) {
            $user->syncPermissions($role->permissions);
        }

        return $role;
    }

    public function delete($id)
    {
        $role = $this->find($id);

        if ($role->users()->count()) {
            throw new ModelHasRelationsException();
        }

        return $role->delete();
    }
}
