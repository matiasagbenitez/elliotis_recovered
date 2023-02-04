<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            [
                'name' => 'admin.countries.index',
                'guard_name' => 'web',
            ],
            [
                'name' => 'admin.provinces.index',
                'guard_name' => 'web',
            ],
            [
                'name' => 'admin.localities.index',
                'guard_name' => 'web',
            ],
            [
                'name' => 'admin.iva-conditions.index',
                'guard_name' => 'web',
            ],
            [
                'name' => 'admin.iva-types.index',
                'guard_name' => 'web',
            ],
            [
                'name' => 'admin.purchase-parameters.index',
                'guard_name' => 'web',
            ],
            [
                'name' => 'admin.clients.index',
                'guard_name' => 'web',
            ],
            [
                'name' => 'admin.clients.create',
                'guard_name' => 'web',
            ],
            [
                'name' => 'admin.clients.edit',
                'guard_name' => 'web',
            ],
            [
                'name' => 'admin.suppliers.index',
                'guard_name' => 'web',
            ],
            [
                'name' => 'admin.suppliers.create',
                'guard_name' => 'web',
            ],
            [
                'name' => 'admin.suppliers.edit',
                'guard_name' => 'web',
            ],
            [
                'name' => 'admin.measures.index',
                'guard_name' => 'web',
            ],
            [
                'name' => 'admin.unities.index',
                'guard_name' => 'web',
            ],
            [
                'name' => 'admin.product-names.index',
                'guard_name' => 'web',
            ],
            [
                'name' => 'admin.wood-types.index',
                'guard_name' => 'web',
            ],
            [
                'name' => 'admin.product-types.index',
                'guard_name' => 'web',
            ],
            [
                'name' => 'admin.products.index',
                'guard_name' => 'web',
            ],
            [
                'name' => 'admin.products.create',
                'guard_name' => 'web',
            ],
            [
                'name' => 'admin.products.show',
                'guard_name' => 'web',
            ],
            [
                'name' => 'admin.products.create-previous-product',
                'guard_name' => 'web',
            ],
            [
                'name' => 'admin.products.create-following-products',
                'guard_name' => 'web',
            ],
            [
                'name' => 'admin.purchases.index',
                'guard_name' => 'web',
            ],
            [
                'name' => 'admin.purchases.create',
                'guard_name' => 'web',
            ],
            [
                'name' => 'admin.purchases.show-detail',
                'guard_name' => 'web',
            ],
            [
                'name' => 'admin.sales.index',
                'guard_name' => 'web',
            ],
            [
                'name' => 'admin.sales.create',
                'guard_name' => 'web',
            ],
            [
                'name' => 'admin.sales.show-detail',
                'guard_name' => 'web',
            ],
            [
                'name' => 'admin.purchase-orders.index',
                'guard_name' => 'web',
            ],
            [
                'name' => 'admin.purchase-orders.create',
                'guard_name' => 'web',
            ],
            [
                'name' => 'admin.purchase-orders.show-detail',
                'guard_name' => 'web',
            ],
            [
                'name' => 'admin.sale-orders.index',
                'guard_name' => 'web',
            ],
            [
                'name' => 'admin.sale-orders.create',
                'guard_name' => 'web',
            ],
            [
                'name' => 'admin.sale-orders.show-detail',
                'guard_name' => 'web',
            ],
            [
                'name' => 'admin.sale-orders.show-necessary-production',
                'guard_name' => 'web',
            ],
            [
                'name' => 'admin.tenderings.index',
                'guard_name' => 'web',
            ],
            [
                'name' => 'admin.tenderings.create',
                'guard_name' => 'web',
            ],
            [
                'name' => 'admin.tenderings.show-detail',
                'guard_name' => 'web',
            ],
            [
                'name' => 'admin.tenderings.show-offer-detail',
                'guard_name' => 'web',
            ],
            [
                'name' => 'admin.tenderings.show-finished-tendering',
                'guard_name' => 'web',
            ],
            [
                'name' => 'admin.parameters.index',
                'guard_name' => 'web',
            ],
            [
                'name' => 'admin.trunk-lots.index',
                'guard_name' => 'web',
            ],
            [
                'name' => 'admin.task-statuses.index',
                'guard_name' => 'web',
            ],
            [
                'name' => 'admin.phases.index',
                'guard_name' => 'web',
            ],
            [
                'name' => 'admin.areas.index',
                'guard_name' => 'web',
            ],
            [
                'name' => 'admin.types-of-tasks.index',
                'guard_name' => 'web',
            ],
            [
                'name' => 'admin.following-products.index',
                'guard_name' => 'web',
            ],
            [
                'name' => 'admin.previous-products.index',
                'guard_name' => 'web',
            ],
            [
                'name' => 'admin.tasks.index',
                'guard_name' => 'web',
            ],
            [
                'name' => 'admin.tasks.manage',
                'guard_name' => 'web',
            ],
            [
                'name' => 'admin.tasks.register',
                'guard_name' => 'web',
            ],
            [
                'name' => 'admin.tasks.show',
                'guard_name' => 'web',
            ],
            [
                'name' => 'admin.lots.index',
                'guard_name' => 'web',
            ],
            [
                'name' => 'admin.sublots.index',
                'guard_name' => 'web',
            ],
            [
                'name' => 'admin.sublots-areas.index',
                'guard_name' => 'web',
            ],
            [
                'name' => 'admin.sublots-products.index',
                'guard_name' => 'web',
            ],
            [
                'name' => 'admin.calculator.index',
                'guard_name' => 'web',
            ],
            [
                'name' => 'admin.notifications.index',
                'guard_name' => 'web',
            ],
            [
                'name' => 'admin.necessary-production.index',
                'guard_name' => 'web',
            ],
            [
                'name' => 'admin.auditory.index',
                'guard_name' => 'web',
            ]
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

    }
}
