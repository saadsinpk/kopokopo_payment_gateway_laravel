<?php

namespace Database\Seeders;

use App\Models\Upload;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UploadsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Upload::create([
            'id' => 1,
            'uuid' => '1c7f8eaa-8a68-4ba5-9347-48ec0d6a438e',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
