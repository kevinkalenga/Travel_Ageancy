<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Destination;

class AdminDestinationController extends Controller
{
    public function index() 
    {
        $destinations = Destination::get();
        return view('admin.destination.index', compact('destinations'));
    }

    public function create() 
    {
        return view('admin.destination.create');
    }

    public function create_submit(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:destinations',
            'slug' => 'required|alpha_dash|unique:destinations',
            'description' => 'required',
            'featured_photo' => ['image', 'mimes:jpg,jpeg,png,gif', 'max:2048'],
        ]);

        $finale_name = 'destination_featured_'.time().'.'.$request->featured_photo->extension();
        $request->featured_photo->move(public_path('uploads'), $finale_name);

        $obj = new Destination();
        $obj->featured_photo = $finale_name;
        $obj->name = $request->name;
        $obj->slug = $request->slug;
        $obj->description = $request->description;
        $obj->country = $request->country;
        $obj->language = $request->language;
        $obj->currency = $request->currency;
        $obj->area = $request->area;
        $obj->timezone = $request->timezone;
        $obj->visa_requirement = $request->visa_requirement;
        $obj->best_time = $request->best_time;
        $obj->health_safety = $request->health_safety;
        $obj->map = $request->map;
        $obj->view_count = 1;
       
        $obj->save();

        return redirect()->route('admin_destination_index')->with('success', 'Destination is Created Successfully');
    }

    public function edit($id)
    {
        $testimonial = Testimonial::where('id', $id)->first();
        return view('admin.testimonial.edit', compact('testimonial'));
    }

    public function edit_submit(Request $request, $id)
    {
        $testimonial = Testimonial::where('id', $id)->first();  
        
        $request->validate([
            'name' => 'required',
            'designation' => 'required',
            'comment' => 'required',
           
        ]);

        if($request->hasFile('photo')) 
        {
            $request->validate([
           
                'photo' => ['image', 'mimes:jpg,jpeg,png,gif', 'max:2048'],
            ]);

            unlink(public_path('uploads/'.$testimonial->photo));

            $finale_name = 'testimonial_'.time().'.'.$request->photo->extension();
            $request->photo->move(public_path('uploads'), $finale_name);
            $testimonial->photo = $finale_name;
        }

        $testimonial->name = $request->name;
        $testimonial->designation = $request->designation;
        $testimonial->comment = $request->comment;
       
        $testimonial->save();

        return redirect()->route('admin_testimonial_index')->with('success', 'Testimonial is Updated Successfully');
    }

    public function delete($id) 
    {
        $testimonial = Testimonial::where('id', $id)->first();
        unlink(public_path('uploads/'.$testimonial->photo));
        $testimonial->delete();

        return redirect()->route('admin_testimonial_index')->with('success', 'Testimonial is Deleted Successfully');
    }
}
