<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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

        $this->call([
            RefreshPermissionsSeeder::class,
            RolesTableSeeder::class,
            SettingsTableSeeder::class,
            PermissionTableSeeder::class,
            MediaTableSeeder::class,
            UploadsTableSeeder::class,
            OfflinePaymentMethodTableSeeder::class,
            CategoriesTableSeeder::class,
            RoleHasPermissionsTableSeeder::class,
            UsersTableSeeder::class,
        ]);
    }
}
