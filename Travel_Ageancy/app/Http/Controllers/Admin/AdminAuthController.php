<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Hash;
use App\Models\Admin;

class AdminAuthController extends Controller
{
    public function login() 
    {
        return view('admin.login');
    }
    public function login_submit(Request $request) 
    {
        $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
       ]);
      //check contain email ans pwd of the request   
       $check = $request->all();
       $data = [
        'email' => $check['email'],
        'password' => $check['password']
       ];
        // it's calling the admin model if everything is ok
        if(Auth::guard('admin')->attempt($data)) {
            return redirect()->route('admin_dashboard')->with('success', 'Login is successful!');
        } else {
          return redirect()->route('admin_login')->with('error','The information you entered is incorrect! Please try again!');
        }
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin_login')->with('success','Logout is successful!');
    }
    
    
    
    public function profile() 
    {
        return view('admin.profile');
    }
    public function forget_password() 
    {
        return view('admin.forget-password');
    }
    public function reset_password() 
    {
        return view('admin.reset-password');
    }
}
