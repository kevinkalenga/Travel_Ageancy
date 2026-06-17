<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class AdminSettingController extends Controller
{
    public function index() 
    {
        
        $setting = Setting::where('id', 1)->first();
        return view('admin.setting.index', compact('setting'));
    }
     public function update(Request $request)
    {
        $obj = Setting::where('id', 1)->first(); 
        
     
        if ($request->hasFile('logo')) {

            $request->validate([
                'logo' => ['image', 'mimes:jpg,jpeg,png,gif,webp', 'max:2048'],
            ]);

            if (!empty($obj->logo) && file_exists(public_path('uploads/'.$obj->logo))) {
                unlink(public_path('uploads/'.$obj->logo));
            }

            $final_name = 'logo_'.time().'.'.$request->logo->extension();

            $request->logo->move(public_path('uploads'), $final_name);

            $obj->logo = $final_name;
        }
        
        
        if ($request->hasFile('fivecon')) {

            $request->validate([
                'fivecon' => ['image', 'mimes:jpg,jpeg,png,gif,webp', 'max:2048'],
            ]);

            if (!empty($obj->fivecon) && file_exists(public_path('uploads/'.$obj->fivecon))) {
                unlink(public_path('uploads/'.$obj->fivecon));
            }

            $finale_name_1 = 'fivecon_'.time().'.'.$request->fivecon->extension();

            $request->fivecon->move(public_path('uploads'), $finale_name_1);

            $obj->fivecon = $finale_name_1;
        }
        
        $obj->top_bar_phone = $request->top_bar_phone;
        $obj->top_bar_email = $request->top_bar_email;
        $obj->footer_address = $request->footer_address;
        $obj->footer_phone = $request->footer_phone;
        $obj->facebook = $request->facebook;
        $obj->twitter = $request->twitter;
        $obj->youtube = $request->youtube;
        $obj->linkedin = $request->linkedin;
        $obj->instagram = $request->instagram;
        $obj->copyright = $request->copyright;
       
        $obj->save();

        return redirect()->back()->with('success', 'Setting is Updated Successfully');
    }
}
