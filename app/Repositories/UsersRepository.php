<?php

namespace App\Repositories;

use App\Exceptions\ModelHasRelationsException;
use App\Models\User;

class UsersRepository extends BaseRepository
{
    public function model()
    {
        return User::class;
    }

    public function create(array $attributes)
    {
        $user = parent::create($attributes);

        $user->roles()->sync([$user->role_id]);

        return $user;
    }

    public function update(array $attributes, $id)
    {
        $user = parent::update($attributes, $id);

        return $user;
    }

    public function delete($id)
    {
        $user = $this->find($id);

        // if ($user->relations()->count()) {
        //     throw new ModelHasRelationsException();
        // }

        return $user->delete();
    }
}
