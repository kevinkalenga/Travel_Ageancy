<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Testimonial;

class AdminTestimonialController extends Controller
{
    public function index() 
    {
        $testimonials = Testimonial::get();
        return view('admin.testimonial.index', compact('testimonials'));
    }

    public function create() 
    {
        return view('admin.testimonial.create');
    }

    public function create_submit(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'designation' => 'required',
            'comment' => 'required',
            'photo' => ['image', 'mimes:jpg,jpeg,png,gif', 'max:2048'],
        ]);

        $finale_name = 'testimonial_'.time().'.'.$request->photo->extension();
        $request->photo->move(public_path('uploads'), $finale_name);

        $obj = new Testimonial();
        $obj->photo = $finale_name;
        $obj->name = $request->name;
        $obj->designation = $request->designation;
        $obj->comment = $request->comment;
       
        $obj->save();

        return redirect()->route('admin_testimonial_index')->with('success', 'Testimonial is Created Successfully');
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
