<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;

class UserController extends Controller
{
    public function dashboard() 
    {
        return view('user.dashboard');
    }
    public function profile() 
    {
        return view('user.profile');
    }

     public function profile_submit(Request $request)
{
    $user = User::where('id', Auth::guard('web')->user()->id)->first();
    
    $request->validate([
        'full_name' => "required",
        'email' => 'required|email|unique:users,email,' . Auth::id(),
        'phone' => "required|string|max:20",
        'country' => "required",
        'address' => "required",
        'state' => "required",
        'city' => "required",
        'zip' => "required|string|max:10",
    ]);

    if($request->photo) {
        $request->validate([
            'photo' => ['image', 'mimes:jpg,jpeg,png,gif', 'max:2048'],
        ]);

         if($user->photo != '' && file_exists(public_path('uploads/' . $user->photo))) {
           unlink(public_path('uploads/' . $user->photo));
          }


        $finale_name = 'user_'.time().'.'.$request->photo->extension();
        $request->photo->move(public_path('uploads'), $finale_name);
        $user->photo = $finale_name;
    } 

    if($request->password != '') {
        $request->validate([
            'password' => 'required',
            'retype_password' => 'required|same:password'
        ]);
        $user->password = bcrypt($request->password);
    }

    // Mise à jour des autres champs
    $user->name = $request->full_name;
    $user->email = $request->email;
    $user->phone = $request->phone;
    $user->country = $request->country;
    $user->address = $request->address;
    $user->state = $request->state;
    $user->city = $request->city;
    $user->zip = $request->zip;

    $user->save();

    return redirect()->back()->with('success', 'Profile updated successfully!');
}

  
}
