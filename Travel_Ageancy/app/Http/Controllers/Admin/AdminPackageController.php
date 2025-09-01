<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\Destination;

class AdminPackageController extends Controller
{
    public function index() 
    {
        $packages = Package::get();
        return view('admin.package.index', compact('packages'));
    }

    public function create() 
    {
        $destinations = Destination::orderBy('name', 'asc')->get();
        return view('admin.package.create', compact('destinations'));
    }

    public function create_submit(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:packages',
            'slug' => 'required|alpha_dash|unique:packages',
            'description' => 'required',
            'price' => 'required|numeric',
            'featured_photo' => ['required','image', 'mimes:jpg,jpeg,png,gif', 'max:2048'],
            'banner' => ['required','image', 'mimes:jpg,jpeg,png,gif', 'max:2048'],
        ]);

        $finale_name = 'package_featured_'.time().'.'.$request->featured_photo->extension();
        $request->featured_photo->move(public_path('uploads'), $finale_name);
        
        $finale_name1 = 'package_banner_'.time().'.'.$request->banner->extension();
        $request->banner->move(public_path('uploads'), $finale_name1);

        $obj = new Package();
        $obj->destination_id = $request->destination_id;
        $obj->featured_photo = $finale_name;
        $obj->banner = $finale_name1;
        $obj->name = $request->name;
        $obj->slug = $request->slug;
        $obj->description = $request->description;
        $obj->price = $request->price;
        $obj->old_price = $request->old_price;
        $obj->map = $request->map;
      
       
        $obj->save();

        return redirect()->route('admin_package_index')->with('success', 'Package is Created Successfully');
    }

    public function edit($id)
    {
        $destination = Destination::where('id', $id)->first();
        return view('admin.destination.edit', compact('destination'));
    }

    public function edit_submit(Request $request, $id)
    {
        $destination = Destination::where('id', $id)->first();  
        
        $request->validate([
               'name' => 'required|unique:destinations,name,'.$id,
               'slug' => 'required|alpha_dash|unique:destinations,slug,'.$id,
               'description' => 'required',
           
        ]);

        if($request->hasFile('featured_photo')) 
        {
            $request->validate([
           
                'featured_photo' => ['image', 'mimes:jpg,jpeg,png,gif', 'max:2048'],
            ]);

            unlink(public_path('uploads/'.$destination->featured_photo));

            $finale_name = 'destination_featured_'.time().'.'.$request->featured_photo->extension();
            $request->featured_photo->move(public_path('uploads'), $finale_name);
            $destination->featured_photo = $finale_name;
        }

        $destination->name = $request->name;
        $destination->slug = $request->slug;
        $destination->description = $request->description;
        $destination->country = $request->country;
        $destination->language = $request->language;
        $destination->currency = $request->currency;
        $destination->area = $request->area;
        $destination->timezone = $request->timezone;
        $destination->visa_requirement = $request->visa_requirement;
        $destination->activity = $request->activity;
        $destination->best_time = $request->best_time;
        $destination->health_safety = $request->health_safety;
        $destination->map = $request->map;
       
        $destination->save();

        return redirect()->route('admin_destination_index')->with('success', 'Destination is Updated Successfully');
    }

    public function delete($id) 
    {
        $total = DestinationPhoto::where('destination_id', $id)->count();
        if($total > 0) {
            return redirect()->back()->with('error', 'First Delete All Photos of This Destination');
        }
        $total1 = DestinationVideo::where('destination_id', $id)->count();
        if($total1 > 0) {
            return redirect()->back()->with('error', 'First Delete All Videos of This Destination');
        }
        $destination = Destination::where('id', $id)->first();
        unlink(public_path('uploads/'.$destination->featured_photo));
        $destination->delete();

        return redirect()->route('admin_destination_index')->with('success', 'Destination is Deleted Successfully');
    }

}
