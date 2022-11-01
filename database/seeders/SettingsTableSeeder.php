<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->delete();

        DB::table('settings')->insert([
            array (
                'id' => 1,
                'key' => 'date_format',
                'value' => 'l jS F Y (H:i:s)',
            ),
            array (
                'id' => 2,
                'key' => 'language',
                'value' => 'en',
            ),
            array (
                'id' => 3,
                'key' => 'app_name',
                'value' => env('APP_NAME', 'Courier Express'),
            ),
            array (
                'id' => 4,
                'key' => 'app_short_description',
                'value' => 'Order a courier on demand',
            ),
            array (
                'id' => 5,
                'key' => 'background_image',
                'value' => '1c7f8eaa-8a68-4ba5-9347-48ec0d6a438e',
            ),
            array(
                'id' => 6,
                'key' => 'header_text',
                'value' => 'The best in town',
            ),
            array(
                'id' => 7,
                'key' => 'subheader_text',
                'value' => 'Find the closest available couriers to you here!',
            ),

        ]);
    }
}
