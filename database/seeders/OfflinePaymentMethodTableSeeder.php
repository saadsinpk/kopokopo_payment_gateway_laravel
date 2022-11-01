<?php

namespace Database\Seeders;

use App\Models\OfflinePaymentMethod;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OfflinePaymentMethodTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OfflinePaymentMethod::create([
            'id' => 1,
            'name' => 'Cash',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'deleted_at' => NULL
        ]);
    }
}
