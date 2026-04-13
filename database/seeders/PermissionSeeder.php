<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'register-employees',
            'view-employees',
            'edit-employees',
            'archive-employees',
            'delete-employees',
        ];
        foreach ($permissions as $permission) {
            Permission::create(['guard_name' => 'web', 'name' => $permission]);
        }

        // update cache to know about the newly created permissions (required if using WithoutModelEvents in seeders)
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        
        $admin = Role::create(['name' => 'admin'])
                ->givePermissionTo(['register-employees', 'view-employees', 'edit-employees', 'archive-employees', 'delete-employees']);

        $employee = Role::create(['name' => 'employee']);

        $super_admin = Role::create(['name' => 'Super-Admin']);
    }
}
