<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\Booking;
use App\Models\Admin;
use App\Models\Review;

class UserController extends Controller
{
    public function dashboard() 
    {
       $total_completed_orders = Booking::where('user_id', Auth::guard('web')->user()->id)->where('paid_status', 'COMPLETED')->count();
       $total_pending_orders = Booking::where('user_id', Auth::guard('web')->user()->id)->where('paid_status', 'PENDING')->count();
        return view('user.dashboard', compact('total_completed_orders', 'total_pending_orders'));
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

    public function booking()
    {
        // $all_data = Booking::with(['tour', 'package'])->where('tour_id', Auth::guard('web')->user()->id)->get();
        $all_data = Booking::with(['tour', 'package'])->where('user_id', Auth::guard('web')->user()->id)->get();
        
       return view('user.booking', compact('all_data'));
    }

    public function invoice($invoice_no)
    {
      $admin_data = Admin::where('id', 1)->first();
      $booking = Booking::with(['tour', 'package'])->where('invoice_no', $invoice_no)->first();
       return view('user.invoice', compact('invoice_no', 'booking', 'admin_data'));
    }

    public function review()
    {
      $reviews = Review::with('package')->where('user_id', Auth::guard('web')->user()->id)->get();
      return view('user.review', compact('reviews'));
    }

  
}
