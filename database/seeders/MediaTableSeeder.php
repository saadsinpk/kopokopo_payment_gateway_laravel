<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('media')->delete();

        Media::create([
            'id' => 1,
            'model_type' => 'App\\Models\\OfflinePaymentMethod',
            'model_id' => 1,
            'uuid' => '6766eafc-0de3-4688-b546-0990f33109db',
            'collection_name' => 'default',
            'name' => '438526',
            'file_name' => '438526.png',
            'mime_type' => 'image/png',
            'disk' => 'public',
            'conversions_disk' => 'public',
            'size' => 49128,
            'manipulations' => '[]',
            'custom_properties' => '[]',
            'generated_conversions' => '{"icon": true}',
            'responsive_images' => '[]',
            'order_column' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Media::create([
            'id' => 9,
            'model_type' => 'App\\Models\\Category',
            'model_id' => 3,
            'uuid' => '2abfcc79-6c6f-4353-9421-feaf7c2cee09',
            'collection_name' => 'default',
            'name' => 'documents-96',
            'file_name' => 'documents-96.png',
            'mime_type' => 'image/png',
            'disk' => 'public',
            'conversions_disk' => 'public',
            'size' => 1031,
            'manipulations' => '[]',
            'custom_properties' => '[]',
            'generated_conversions' => '{"icon": true}',
            'responsive_images' => '[]',
            'order_column' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Media::create([
            'id' => 10,
            'model_type' => 'App\\Models\\Category',
            'model_id' => 4,
            'uuid' => 'dcbdddd0-c1b7-44d1-b5b1-033c8b2bc225',
            'collection_name' => 'default',
            'name' => 'clothes-96',
            'file_name' => 'clothes-96.png',
            'mime_type' => 'image/png',
            'disk' => 'public',
            'conversions_disk' => 'public',
            'size' => 2219,
            'manipulations' => '[]',
            'custom_properties' => '[]',
            'generated_conversions' => '{"icon": true}',
            'responsive_images' => '[]',
            'order_column' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Media::create([
            'id' => 11,
            'model_type' => 'App\\Models\\Category',
            'model_id' => 5,
            'uuid' => 'f4df47fd-da1d-431d-b165-6f06ac0aab99',
            'collection_name' => 'default',
            'name' => 'box-64',
            'file_name' => 'box-64.png',
            'mime_type' => 'image/png',
            'disk' => 'public',
            'conversions_disk' => 'public',
            'size' => 1511,
            'manipulations' => '[]',
            'custom_properties' => '[]',
            'generated_conversions' => '{"icon": true}',
            'responsive_images' => '[]',
            'order_column' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Media::create([
            'id' => 12,
            'model_type' => 'App\\Models\\Category',
            'model_id' => 6,
            'uuid' => 'c49c5c99-2c85-4ea0-93a3-892304d7fbb0',
            'collection_name' => 'default',
            'name' => 'take-away-food-96',
            'file_name' => 'take-away-food-96.png',
            'mime_type' => 'image/png',
            'disk' => 'public',
            'conversions_disk' => 'public',
            'size' => 723,
            'manipulations' => '[]',
            'custom_properties' => '[]',
            'generated_conversions' => '{"icon": true}',
            'responsive_images' => '[]',
            'order_column' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Media::create([
            'id' => 13,
            'model_type' => 'App\\Models\\Category',
            'model_id' => 7,
            'uuid' => '8a9384bc-b095-40bd-9586-c82e4a5c065f',
            'collection_name' => 'default',
            'name' => 'pizza-96',
            'file_name' => 'pizza-96.png',
            'mime_type' => 'image/png',
            'disk' => 'public',
            'conversions_disk' => 'public',
            'size' => 4251,
            'manipulations' => '[]',
            'custom_properties' => '[]',
            'generated_conversions' => '{"icon": true}',
            'responsive_images' => '[]',
            'order_column' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Media::create([
            'id' => 14,
            'model_type' => 'App\\Models\\Category',
            'model_id' => 8,
            'uuid' => 'f802ac26-1203-48a8-b3a7-95969fc07678',
            'collection_name' => 'default',
            'name' => 'medicine-96',
            'file_name' => 'medicine-96.png',
            'mime_type' => 'image/png',
            'disk' => 'public',
            'conversions_disk' => 'public',
            'size' => 3127,
            'manipulations' => '[]',
            'custom_properties' => '[]',
            'generated_conversions' => '{"icon": true}',
            'responsive_images' => '[]',
            'order_column' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Media::create([
            'id' => 15,
            'model_type' => 'App\\Models\\Category',
            'model_id' => 9,
            'uuid' => 'b2fcab06-4020-4f69-8fa0-d1f8270bc7fe',
            'collection_name' => 'default',
            'name' => 'products-64',
            'file_name' => 'products-64.png',
            'mime_type' => 'image/png',
            'disk' => 'public',
            'conversions_disk' => 'public',
            'size' => 1610,
            'manipulations' => '[]',
            'custom_properties' => '[]',
            'generated_conversions' => '{"icon": true}',
            'responsive_images' => '[]',
            'order_column' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Media::create([
            'id' => 16,
            'model_type' => 'App\\Models\\Category',
            'model_id' => 10,
            'uuid' => 'd615b629-383c-4f56-9692-016e2f778279',
            'collection_name' => 'default',
            'name' => 'shopping-cart-60',
            'file_name' => 'shopping-cart-60.png',
            'mime_type' => 'image/png',
            'disk' => 'public',
            'conversions_disk' => 'public',
            'size' => 2019,
            'manipulations' => '[]',
            'custom_properties' => '[]',
            'generated_conversions' => '{"icon": true}',
            'responsive_images' => '[]',
            'order_column' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Media::create([
            'id' => 17,
            'model_type' => 'App\\Models\\Category',
            'model_id' => 11,
            'uuid' => '3a952353-0bed-4fe3-ac3a-c7044db9b5f0',
            'collection_name' => 'default',
            'name' => 'mechanical-tools-66',
            'file_name' => 'mechanical-tools-66.png',
            'mime_type' => 'image/png',
            'disk' => 'public',
            'conversions_disk' => 'public',
            'size' => 2659,
            'manipulations' => '[]',
            'custom_properties' => '[]',
            'generated_conversions' => '{"icon": true}',
            'responsive_images' => '[]',
            'order_column' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Media::create([
            'id' => 18,
            'model_type' => 'App\\Models\\Category',
            'model_id' => 12,
            'uuid' => '99045f25-a1ad-4e7a-b26b-818bf5be14b5',
            'collection_name' => 'default',
            'name' => 'presents-60',
            'file_name' => 'presents-60.png',
            'mime_type' => 'image/png',
            'disk' => 'public',
            'conversions_disk' => 'public',
            'size' => 2520,
            'manipulations' => '[]',
            'custom_properties' => '[]',
            'generated_conversions' => '{"icon": true}',
            'responsive_images' => '[]',
            'order_column' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Media::create([
            'id' => 19,
            'model_type' => 'App\\Models\\Category',
            'model_id' => 13,
            'uuid' => '877a9da9-4efb-4af6-bd24-dd8e1d32667a',
            'collection_name' => 'default',
            'name' => 'more-details-80',
            'file_name' => 'more-details-80.png',
            'mime_type' => 'image/png',
            'disk' => 'public',
            'conversions_disk' => 'public',
            'size' => 679,
            'manipulations' => '[]',
            'custom_properties' => '[]',
            'generated_conversions' => '{"icon": true}',
            'responsive_images' => '[]',
            'order_column' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Media::create([
            'id' => 20,
            'model_type' => 'App\\Models\\Upload',
            'model_id' => 1,
            'uuid' => '1c7f8eaa-8a68-4ba5-9347-48ec0d6a438e',
            'collection_name' => 'default',
            'name' => 'site_header',
            'file_name' => 'site_header.png',
            'mime_type' => 'image/png',
            'disk' => 'public',
            'conversions_disk' => 'public',
            'size' => 224939,
            'manipulations' => '[]',
            'custom_properties' => '[]',
            'generated_conversions' => '{"thumb": true}',
            'responsive_images' => '[]',
            'order_column' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
