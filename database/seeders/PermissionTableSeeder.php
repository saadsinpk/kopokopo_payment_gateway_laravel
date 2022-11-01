<?php

namespace Database\Seeders;

use App\Models\Permission;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->delete();

        Permission::create([
            'id' => 30,
            'name' => 'admin.dashboard',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 31,
            'name' => 'admin.dashboard.ajaxGetOrders',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 32,
            'name' => 'admin.couriers.index',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 33,
            'name' => 'admin.couriers.show',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 34,
            'name' => 'admin.couriers.edit',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 35,
            'name' => 'admin.couriers.update',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 36,
            'name' => 'admin.couriers.destroy',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 37,
            'name' => 'admin.settings.general',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 38,
            'name' => 'admin.settings.app',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 39,
            'name' => 'admin.settings.pricing',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 40,
            'name' => 'admin.settings.translations',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 41,
            'name' => 'admin.settings.social_login',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 42,
            'name' => 'admin.settings.payments_api',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 43,
            'name' => 'admin.settings.notifications',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 44,
            'name' => 'admin.settings.legal',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 45,
            'name' => 'admin.settings.currency',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 46,
            'name' => 'admin.settings.clear_cache',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 47,
            'name' => 'admin.roles.index',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 48,
            'name' => 'admin.roles.create',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 49,
            'name' => 'admin.roles.store',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 50,
            'name' => 'admin.roles.show',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 51,
            'name' => 'admin.roles.edit',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 52,
            'name' => 'admin.roles.update',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 53,
            'name' => 'admin.roles.destroy',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 54,
            'name' => 'admin.offlinePaymentMethods.index',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 55,
            'name' => 'admin.offlinePaymentMethods.create',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 56,
            'name' => 'admin.offlinePaymentMethods.store',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 57,
            'name' => 'admin.offlinePaymentMethods.show',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 58,
            'name' => 'admin.offlinePaymentMethods.edit',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 59,
            'name' => 'admin.offlinePaymentMethods.update',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 60,
            'name' => 'admin.offlinePaymentMethods.destroy',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 61,
            'name' => 'admin.permissions.index',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 62,
            'name' => 'admin.permissions.update',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 63,
            'name' => 'admin.settings.saveSettings',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 64,
            'name' => 'admin.users.index',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 65,
            'name' => 'admin.users.create',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 66,
            'name' => 'admin.users.store',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 67,
            'name' => 'admin.users.show',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 68,
            'name' => 'admin.users.edit',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 69,
            'name' => 'admin.users.update',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 70,
            'name' => 'admin.users.destroy',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 71,
            'name' => 'admin.users.login_as',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 72,
            'name' => 'admin.orders.ajaxGetAddressesHtml',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 73,
            'name' => 'admin.orders.index',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 74,
            'name' => 'admin.orders.show',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 75,
            'name' => 'admin.orders.edit',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 76,
            'name' => 'admin.orders.update',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 77,
            'name' => 'admin.orders.destroy',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 78,
            'name' => 'admin.courierPayouts.courierTable',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 79,
            'name' => 'admin.courierPayouts.courierSummary',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 80,
            'name' => 'admin.courierPayouts.index',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 81,
            'name' => 'admin.courierPayouts.create',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 82,
            'name' => 'admin.courierPayouts.store',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 83,
            'name' => 'admin.courierPayouts.show',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 84,
            'name' => 'admin.courierPayouts.edit',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 85,
            'name' => 'admin.courierPayouts.update',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 86,
            'name' => 'admin.courierPayouts.destroy',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 87,
            'name' => 'admin.reports.ordersByDate',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 88,
            'name' => 'admin.reports.ordersByDriver',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 89,
            'name' => 'admin.reports.ordersByCustomer',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 90,
            'name' => 'admin.categories.index',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 91,
            'name' => 'admin.categories.create',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 92,
            'name' => 'admin.categories.store',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 93,
            'name' => 'admin.categories.show',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 94,
            'name' => 'admin.categories.edit',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 95,
            'name' => 'admin.categories.update',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 96,
            'name' => 'admin.categories.destroy',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 97,
            'name' => 'home',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 98,
            'name' => 'admin.couriersJson',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 99,
            'name' => 'admin.customersJson',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
