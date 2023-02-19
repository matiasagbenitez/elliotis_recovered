<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $role1 = Role::create(['name' => 'sudo']);
        $role1->givePermissionTo(Permission::all());
        $role1->revokePermissionTo('admin.auditory.index');

        $role2 = Role::create(['name' => 'auditor']);
        $role2->givePermissionTo('admin.auditory.index');

        $role3 = Role::create(['name' => 'administrador']);
        $role3->givePermissionTo(Permission::all());
        $role3->revokePermissionTo('admin.auditory.index');
        $role3->revokePermissionTo('admin.products.create');
        $role3->revokePermissionTo('admin.products.create-previous-product');
        $role3->revokePermissionTo('admin.products.create-following-products');
        $role3->revokePermissionTo('admin.roles.create');
        $role3->revokePermissionTo('admin.roles.edit');

        $role4 = Role::create(['name' => 'empleado']);
        $role4->givePermissionTo(
            'admin.trunk-lots.index',
            'admin.products.index',
            'admin.tasks.index',
            'admin.tasks.manage',
            'admin.tasks.register',
            'admin.tasks.show',
            'admin.lots.index',
            'admin.sublots.index',
            'admin.sublots-areas.index',
            'admin.sublots-products.index',
            'admin.necessary-production.index',
            'admin.calculator.index',
        );
    }
}
