<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'create posts']);
        Permission::create(['name' => 'edit posts']);
        Permission::create(['name' => 'delete posts']);
        Permission::create(['name' => 'publish posts']);
        
        Permission::create(['name' => 'create users']);
        Permission::create(['name' => 'view users']);
        Permission::create(['name' => 'edit users']);
        Permission::create(['name' => 'delete users']);

        $adminRole = Role::findByName('admin');
        $userRole = Role::findByName('user');
        $editorRole = Role::findByName('editor');

        $adminRole->givePermissionTo(Permission::all());

        $editorRole->givePermissionTo([
            'create posts',
            'edit posts',
            'publish posts'
        ]);

        $userRole->givePermissionTo(['create users','view users','edit users']);

        //Another way to create permissions 
        $permissions = [
            'create posts',
            'edit posts',
            'delete posts',
            'publish posts',
            'create users',
            'edit users',
            'delete users',
            'view analytics',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

    }
}
