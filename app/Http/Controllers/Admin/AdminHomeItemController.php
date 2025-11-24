<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HomeItems;

class AdminHomeItemController extends Controller
{
    public function index() 
    {
        // I'm going to get the data from $welcome_item
        $home_item = HomeItems::where('id', 1)->first();
        return view('admin.home_item.index', compact('home_item'));
    }
     public function update(Request $request)
    {
        $obj = HomeItems::where('id', 1)->first();  
        
        $request->validate([
          'destination_heading' => 'required',
          'destination_subheading' => 'required',
          'package_heading' => 'required',
          'package_subheading' => 'required',
          'testimonial_heading' => 'required',
          'testimonial_subheading' => 'required',
          'blog_heading' => 'required',
          'blog_subheading' => 'required',
           
           
        ]);


            if ($request->hasFile('testimonial_background')) 
            {
                $request->validate([
        '            testimonial_background' => 'image|mimes:jpg,jpeg,png,gif|max:2048',
                ]);

                // Vérifier que l'ancien fichier existe avant unlink
                if ($obj->testimonial_background && file_exists(public_path('uploads/'.$obj->testimonial_background))) {
                    unlink(public_path('uploads/'.$obj->testimonial_background));
                }

                // Génération du nouveau nom
                $final_name = 'testimonial_'.time().'.'.$request->testimonial_background->extension();

                // Upload du fichier
                $request->testimonial_background->move(public_path('uploads'), $final_name);

                // Mise à jour dans la base
                $obj->testimonial_background = $final_name;
            }
        
        
        
       

        

        $obj->destination_heading = $request->destination_heading;
        $obj->destination_subheading = $request->destination_subheading;
        $obj->destination_status = $request->destination_status;
        $obj->feature_status = $request->feature_status;
        $obj->package_heading = $request->package_heading;
        $obj->package_subheading = $request->package_subheading;
        $obj->package_status = $request->package_status;
        $obj->testimonial_heading = $request->testimonial_heading;
        $obj->testimonial_subheading = $request->testimonial_subheading;
        $obj->testimonial_status = $request->testimonial_status;
        $obj->blog_heading = $request->blog_heading;
        $obj->blog_subheading = $request->blog_subheading;
        $obj->blog_status = $request->blog_status;
        
        $obj->save();

        return redirect()->back()->with('success', 'HomeItems is Updated Successfully');
    }
}
