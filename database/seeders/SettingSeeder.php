<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $obj = new Setting;
         $obj->logo = '';
         $obj->fivecon = '';
         $obj->top_bar_phone = '111-222-3333';
         $obj->top_bar_email = 'contact@example.com';
         $obj->footer_address = '34 Antiger Lane, USA, 12937';
         $obj->footer_phone = '122-222-1212';
         $obj->footer_email = 'contact@example.com';
         $obj->facebook = '#';
         $obj->twitter = '#';
         $obj->youtube = '#';
         $obj->linkedin = '#';
         $obj->instagram = '#';
         $obj->copyright = 'Copyright © 2025, TripSummit. All Rights Reserved.';
         $obj->banner = '';
         $obj->save();
    }
}
