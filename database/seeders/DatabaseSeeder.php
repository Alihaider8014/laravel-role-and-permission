<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $adminRole = Role::create(['name' => 'admin']);
        $editorRole = Role::create(['name' => 'editor']);
        $addRole = Role::create(['name' => 'add']);
        $viewerRole = Role::create(['name' => 'viewer']);
        $deleteRole = Role::create(['name' => 'delete']);

        $editArticlesPermission = Permission::create(['name' => 'edit articles']);
        $addArticlesPermission = Permission::create(['name' => 'add articles']);
        $viewerArticlesPermission = Permission::create(['name' => 'view articles']);
        $deleteArticlesPermission = Permission::create(['name' => 'delete articles']);

        $adminRole->permissions()->attach([$editArticlesPermission->id, $deleteArticlesPermission->id, $viewerArticlesPermission->id, $addArticlesPermission->id]);
        $editorRole->permissions()->attach($editArticlesPermission->id);
        $addRole->permissions()->attach($addArticlesPermission->id);
        $viewerRole->permissions()->attach($viewerArticlesPermission->id);
        $deleteRole->permissions()->attach($deleteArticlesPermission->id);
    }
}
