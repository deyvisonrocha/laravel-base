<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SeedUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $admin = User::create(['name' => 'Administrador', 'email' => 'admin@admin.com', 'password' => 'admin', 'role_id' => 1]);
        $roleAdmin = Role::find(1);
        $admin->assignRole($roleAdmin);
        $admin->givePermissionTo(Permission::pluck('name'));
        $roleAdmin->givePermissionTo(Permission::pluck('name'));

        $user = User::create(['name' => 'Usuário', 'email' => 'user@user.com', 'password' => 'user', 'role_id' => 2]);
        $roleUser = Role::find(2);
        $user->assignRole($roleUser);
        $permissions = [
            Permission::where('name', 'roles.edit')->pluck('name'),
            Permission::where('name', 'roles.index')->pluck('name')
        ];
        $user->givePermissionTo($permissions);
        $roleUser->givePermissionTo($permissions);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
