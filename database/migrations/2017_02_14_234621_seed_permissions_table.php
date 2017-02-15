<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;

class SeedPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Roles
        Permission::create(['name' => 'roles.index', 'module' => 'Perfil', 'label' => 'Listar']);
        Permission::create(['name' => 'roles.create', 'module' => 'Perfil', 'label' => 'Criar']);
        Permission::create(['name' => 'roles.edit', 'module' => 'Perfil', 'label' => 'Editar']);
        Permission::create(['name' => 'roles.destroy', 'module' => 'Perfil', 'label' => 'Remover']);
        Permission::create(['name' => 'roles.show', 'module' => 'Perfil', 'label' => 'Visualizar']);

        // Users
        Permission::create(['name' => 'users.index', 'module' => 'Usuário', 'label' => 'Listar']);
        Permission::create(['name' => 'users.create', 'module' => 'Usuário', 'label' => 'Criar']);
        Permission::create(['name' => 'users.edit', 'module' => 'Usuário', 'label' => 'Editar']);
        Permission::create(['name' => 'users.destroy', 'module' => 'Usuário', 'label' => 'Remover']);
        Permission::create(['name' => 'users.show', 'module' => 'Usuário', 'label' => 'Visualizar']);
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
