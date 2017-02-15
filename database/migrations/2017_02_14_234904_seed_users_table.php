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

        $role = Role::find(1);

        $admin->assignRole($role);
        $admin->givePermissionTo(Permission::pluck('name'));
        $role->givePermissionTo(Permission::pluck('name'));
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
