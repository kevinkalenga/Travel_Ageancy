<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AboutItems;

class AdminAboutItemController extends Controller
{
    public function index() 
    {
        // I'm going to get the data from $welcome_item
        $about_item = AboutItems::where('id', 1)->first();
        return view('admin.about_item.index', compact('about_item'));
    }
     public function update(Request $request)
    {
        $obj = AboutItems::where('id', 1)->first();  
        
        $obj->feature_status = $request->feature_status;
        $obj->save();

        return redirect()->back()->with('success', 'AboutItems is Updated Successfully');
    }
}
