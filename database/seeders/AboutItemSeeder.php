<?php

namespace Database\Seeders;
use App\Models\AboutItems;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AboutItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $obj = new AboutItems;
         $obj->feature_status = "Show";
         $obj->save();
    }
}
