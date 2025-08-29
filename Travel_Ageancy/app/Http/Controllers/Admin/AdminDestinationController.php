<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Destination;
use App\Models\DestinationPhoto;
use App\Models\DestinationVideo;

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
        $obj->activity = $request->activity;
        $obj->best_time = $request->best_time;
        $obj->health_safety = $request->health_safety;
        $obj->map = $request->map;
        $obj->view_count = 1;
       
        $obj->save();

        return redirect()->route('admin_destination_index')->with('success', 'Destination is Created Successfully');
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

    public function destination_photos($id)
    {
        $destination = Destination::where('id', $id)->first();
        $destination_photos = DestinationPhoto::where('destination_id', $id)->get();
        return view('admin.destination.photos', compact('destination', 'destination_photos'));
    }
public function destination_photo_submit(Request $request, $id)
{
    // Vérifie qu'un fichier a été sélectionné
    if (!$request->hasFile('photo')) {
        return redirect()->back()->with('error', 'Veuillez sélectionner un fichier.');
    }

    // Validation du fichier
    $request->validate([
        'photo' => ['image', 'mimes:jpg,jpeg,png,gif', 'max:2048'],
    ]);

    // Crée un nom unique pour le fichier
    $finale_name = 'destination_' . time() . '.' . $request->photo->extension();

    // Déplace le fichier dans le dossier uploads
    $request->photo->move(public_path('uploads'), $finale_name);

    // Sauvegarde dans la base
    $obj = new DestinationPhoto;
    $obj->destination_id = $id;
    $obj->photo = $finale_name;
    $obj->save();

    return redirect()->back()->with('success', 'Photo insérée avec succès');
}

public function destination_photo_delete($id)
{
    $destination_photo = DestinationPhoto::where('id', $id)->first();
    unlink(public_path('uploads/'.$destination_photo->photo));
    $destination_photo->delete();
    return redirect()->back()->with('Success', 'Photo is deleted successfully');
}

    public function destination_videos($id)
    {
        $destination = Destination::where('id', $id)->first();
        $destination_videos = DestinationVideo::where('destination_id', $id)->get();
        return view('admin.destination.videos', compact('destination', 'destination_videos'));
    }
public function destination_video_submit(Request $request, $id)
{
    

    // Validation du fichier
    $request->validate([
        'video' => "required",
    ]);

    // Sauvegarde dans la base
    $obj = new DestinationVideo;
    $obj->destination_id = $id;
    $obj->video = $request->video;
    $obj->save();

    return redirect()->back()->with('success', 'Video insérée avec succès');
}

public function destination_video_delete($id)
{
    $destination_video = DestinationVideo::where('id', $id)->first();
    $destination_video->delete();
    return redirect()->back()->with('Success', 'Video is deleted successfully');
}




}
