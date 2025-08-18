<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Feature;

class AdminFeatureController extends Controller
{
    public function index() 
    {
        // Show the feature section in the home page
        $features = Feature::get();
        return view('admin.feature.index', compact('features'));
    }

    public function create() 
    {
        return view('admin.feature.create');
    }

    public function create_submit(Request $request)
    {
        $request->validate([
            'icon' => 'required',
            'heading' => 'required',
            'description' => 'required',
            
            
        ]);

       

        $obj = new Feature();
        $obj->icon = $request->icon;
        $obj->heading = $request->heading;
        $obj->description = $request->description;
        $obj->save();

        return redirect()->route('admin_feature_index')->with('success', 'Feature is Created Successfully');
    }

    public function edit($id)
    {
        $slider = Slider::where('id', $id)->first();
        return view('admin.slider.edit', compact('slider'));
    }

    public function edit_submit(Request $request, $id)
    {
        $slider = Slider::where('id', $id)->first();  
        
        $request->validate([
            'heading' => 'required',
            'text' => 'required',
           
        ]);

        if($request->hasFile('photo')) 
        {
            $request->validate([
           
                'photo' => ['image', 'mimes:jpg,jpeg,png,gif', 'max:2048'],
            ]);

            unlink(public_path('uploads/'.$slider->photo));

            $finale_name = 'slider_'.time().'.'.$request->photo->extension();
            $request->photo->move(public_path('uploads'), $finale_name);
            $slider->photo = $finale_name;
        }

        $slider->heading = $request->heading;
        $slider->text = $request->text;
        $slider->button_text = $request->button_text;
        $slider->button_link = $request->button_link;
        $slider->save();

        return redirect()->route('admin_slider_index')->with('success', 'Slider Updated Successfully');
    }

    public function delete($id) 
    {
        $slider = Slider::where('id', $id)->first();
        unlink(public_path('uploads/'.$slider->photo));
        $slider->delete();

        return redirect()->route('admin_slider_index')->with('success', 'Slider Deleted Successfully');
    }
}
