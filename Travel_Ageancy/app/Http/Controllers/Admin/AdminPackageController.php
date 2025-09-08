<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\Destination;
use App\Models\PackageAmenity;
use App\Models\PackageItinerary;
use App\Models\PackagePhoto;
use App\Models\Amenity;

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
            'old_price' => 'numeric',
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
        $package = Package::where('id', $id)->first();
        $destinations = Destination::orderBy('name', 'asc')->get();
        return view('admin.package.edit', compact('package', 'destinations'));
    }

    public function edit_submit(Request $request, $id)
    {
        $package = Package::where('id', $id)->first();  
        
        $request->validate([
               'name' => 'required|unique:packages,name,'.$id,
               'slug' => 'required|alpha_dash|unique:packages,slug,'.$id,
               'description' => 'required',
               'price' => 'required|numeric',
               'old_price' => 'numeric',
               
           
        ]);

        if ($request->hasFile('featured_photo')) {
             $request->validate([
               'featured_photo' => ['image', 'mimes:jpg,jpeg,png,gif', 'max:2048'],
             ]);

            // Supprimer l'ancienne photo si elle existe
           if ($package->featured_photo && file_exists(public_path('uploads/' . $package->featured_photo))) {
                 unlink(public_path('uploads/' . $package->featured_photo));
           }

             // Uploader la nouvelle photo
             $final_name = 'package_featured_' . time() . '.' . $request->featured_photo->extension();
             $request->featured_photo->move(public_path('uploads'), $final_name);
             $package->featured_photo = $final_name;
        }
        if($request->hasFile('banner')) 
        {
            $request->validate([
           
                'banner' => ['image', 'mimes:jpg,jpeg,png,gif', 'max:2048'],
            ]);

               // Supprimer l'ancienne bannière si elle existe
               if ($package->banner && file_exists(public_path('uploads/' . $package->banner))) {
                  unlink(public_path('uploads/' . $package->banner));
                }

            $finale_name1 = 'package_banner_'.time().'.'.$request->banner->extension();
            $request->banner->move(public_path('uploads'), $finale_name1);
            $package->banner = $finale_name1;
        }

        $package->destination_id = $request->destination_id;
        $package->name = $request->name;
        $package->slug = $request->slug;
        $package->description = $request->description;
        $package->price = $request->price;
        $package->old_price = $request->old_price;
        $package->map = $request->map;
      
       
        $package->save();

        return redirect()->route('admin_package_index')->with('success', 'Package is Updated Successfully');
    }

    public function delete($id) 
    {
        // $total = DestinationPhoto::where('destination_id', $id)->count();
        // if($total > 0) {
        //     return redirect()->back()->with('error', 'First Delete All Photos of This Destination');
        // }
        // $total1 = DestinationVideo::where('destination_id', $id)->count();
        // if($total1 > 0) {
        //     return redirect()->back()->with('error', 'First Delete All Videos of This Destination');
        // }
     


          $total3 = PackageAmenity::where('package_id', $id)->count();
          if($total3 > 0) {
              return redirect()->back()->with('error', 'First Delete All Amenity of This Package');
          }
         $package = Package::where('id', $id)->first();
         unlink(public_path('uploads/'.$package->featured_photo));
         unlink(public_path('uploads/'.$package->banner));
        $package->delete();

        return redirect()->route('admin_package_index')->with('success', 'Package is Deleted Successfully');
    }


    public function package_amenities($id)
    {
        $package = Package::where('id', $id)->first();
        $package_amenities_include = PackageAmenity::with('amenity')->where('package_id', $id)->where('type', 'Include')->get();
        $package_amenities_exclude = PackageAmenity::with('amenity')->where('package_id', $id)->where('type', 'Exclude')->get();
        $amenities = Amenity::orderBy('name', 'asc')->get();
        return view('admin.package.amenities', compact('package', 'package_amenities_include', 'package_amenities_exclude', 'amenities'));
    }


public function package_amenity_submit(Request $request, $id)
{
    $total = PackageAmenity::where('package_id', $id)->where('amenity_id', $request->amenity_id)->count();
     
    if($total > 0) {
        return redirect()->back()->with('error', 'This Item is already Inserted');
    }
    // Sauvegarde dans la base
    $obj = new PackageAmenity;
    $obj->package_id = $id;
    $obj->amenity_id = $request->amenity_id;
    $obj->type = $request->type;
    $obj->save();

    return redirect()->back()->with('success', 'Item inserted avec succès');
}

public function package_amenity_delete($id)
{
    $obj = PackageAmenity::where('id', $id)->first();
    $obj->delete();
    return redirect()->back()->with('Success', 'Item is deleted successfully');
}





public function package_itineraries($id)
{
        $package = Package::where('id', $id)->first();
        $package_itineraries = PackageItinerary::where('package_id', $id)->get();
        return view('admin.package.itineraries', compact('package', 'package_itineraries'));
}


public function package_itinerary_submit(Request $request, $id)
{
    $request->validate([
       'name' => 'required',
       'description' => 'required',
    ]);
    
    
    // Sauvegarde dans la base
    $obj = new PackageItinerary;
    $obj->package_id = $id;
    $obj->name = $request->name;
    $obj->description = $request->description;
    $obj->save();

    return redirect()->back()->with('success', 'Item inserted avec succès');
}

public function package_itinerary_delete($id)
{
    $obj = PackageItinerary::where('id', $id)->first();
    $obj->delete();
    return redirect()->back()->with('Success', 'Item is deleted successfully');
}


  public function package_photos($id)
    {
        $package = Package::where('id', $id)->first();
        $package_photos = PackagePhoto::where('package_id', $id)->get();
        return view('admin.package.photos', compact('package', 'package_photos'));
    }
public function package_photo_submit(Request $request, $id)
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
    $finale_name = 'package_' . time() . '.' . $request->photo->extension();

    // Déplace le fichier dans le dossier uploads
    $request->photo->move(public_path('uploads'), $finale_name);

    // Sauvegarde dans la base
    $obj = new PackagePhoto;
    $obj->package_id = $id;
    $obj->photo = $finale_name;
    $obj->save();

    return redirect()->back()->with('success', 'Photo insérée avec succès');
}

public function photo_delete($id)
{
    $package_photo = PackagePhoto::where('id', $id)->first();
    if($package_photo && file_exists(public_path('uploads/'.$package_photo->photo))) {
       unlink(public_path('uploads/'.$package_photo->photo));
     }
    $package_photo->delete();
    return redirect()->back()->with('Success', 'Photo is deleted successfully');
}




}
