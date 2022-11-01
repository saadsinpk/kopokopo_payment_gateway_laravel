<?php

namespace Database\Seeders;

use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create([
            'id' => 3,
            'name' => 'Documents',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);



        Category::create([
            'id' => 4,
            'name' => 'Clothes',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);



        Category::create([
            'id' => 5,
            'name' => 'Boxes',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);



        Category::create([
            'id' => 6,
            'name' => 'Bags',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);



        Category::create([
            'id' => 7,
            'name' => 'Food',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);



        Category::create([
            'id' => 8,
            'name' => 'Medicines',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);



        Category::create([
            'id' => 9,
            'name' => 'Products',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);



        Category::create([
            'id' => 10,
            'name' => 'Shopping',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);



        Category::create([
            'id' => 11,
            'name' => 'Tools',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);



        Category::create([
            'id' => 12,
            'name' => 'Gifts',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);



        Category::create([
            'id' => 13,
            'name' => 'Others',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
