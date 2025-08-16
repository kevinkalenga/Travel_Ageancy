<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WelcomeItem;

class AdminWelcomeItemController extends Controller
{
    public function index() 
    {
        // I'm going to get the data from $welcome_item
        $welcome_item = WelcomeItem::where('id', 1)->first();
        return view('admin.welcome.index', compact('welcome_item'));
    }
     public function update(Request $request)
    {
        $obj = WelcomeItem::where('id', 1)->first();  
        
        $request->validate([
            'heading' => 'required',
            'description' => 'required',
            'video' => 'required',
           
        ]);

        if($request->hasFile('photo')) 
        {
            $request->validate([
           
                'photo' => ['image', 'mimes:jpg,jpeg,png,gif', 'max:2048'],
            ]);

            unlink(public_path('uploads/'.$obj->photo));

            $finale_name = 'welcome_item_'.time().'.'.$request->photo->extension();
            $request->photo->move(public_path('uploads'), $finale_name);
            $obj->photo = $finale_name;
        }

        $obj->heading = $request->heading;
        $obj->description = $request->description;
        $obj->button_text = $request->button_text;
        $obj->button_link = $request->button_link;
        $obj->video = $request->video;
        $obj->status = $request->status;
        $obj->save();

        return redirect()->back()->with('success', 'Welcome Item Updated Successfully');
    }
}
