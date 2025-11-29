<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Message;
use App\Models\Review;
use App\Models\Wishlist;
use App\Models\Booking;
use App\Models\MessageComment;
use App\Mail\Websitemail;

class AdminUserController extends Controller
{
    public function users()
    {
      $users = User::get();
      return view('admin.user.users', compact('users'));
    }
  
    public function user_create()
    {
      return view('admin.user.user_create');
    }
    
    public function user_create_submit(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'required',
            'country' => 'required',
            'address' => 'required',
            'state' => 'required',
            'city' => 'required',
            'zip' => 'required',
            'password' => 'required',
            'photo' => ['image', 'mimes:jpg,jpeg,png,gif', 'max:2048'],
        ]);

        $finale_name = 'user_'.time().'.'.$request->photo->extension();
        $request->photo->move(public_path('uploads'), $finale_name);

        $obj = new User();
        $obj->name = $request->name;
        $obj->email = $request->email;
        $obj->phone = $request->phone;
        $obj->country = $request->country;
        $obj->address = $request->address;
        $obj->state = $request->state;
        $obj->city = $request->city;
        $obj->zip = $request->zip;
        $obj->password = $request->password;
        $obj->photo = $finale_name;
         $obj->status = $request->status;
        $obj->save();

        return redirect()->route('admin_users')->with('success', 'User is Created Successfully');
    }
    
    
    public function user_edit($id)
    {
        $user = User::where('id', $id)->first();
        return view('admin.user.user_edit', compact('user'));
    }
    
    
    
    public function user_edit_submit(Request $request, $id)
    {
        $obj = User::where('id', $id)->first();  
        
         $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'phone' => 'required',
            'country' => 'required',
            'address' => 'required',
            'state' => 'required',
            'city' => 'required',
            'zip' => 'required',
            
        ]);

        if($request->hasFile('photo')) 
        {
            $request->validate([
           
                'photo' => ['image', 'mimes:jpg,jpeg,png,gif', 'max:2048'],
            ]);

            unlink(public_path('uploads/'.$obj->photo));

            $finale_name = 'user_'.time().'.'.$request->photo->extension();
            $request->photo->move(public_path('uploads'), $finale_name);
            $obj->photo = $finale_name;
        }

        if ($request->password != '') {
           $obj->password = bcrypt($request->password);
        }

        $obj->name = $request->name;
        $obj->email = $request->email;
        $obj->phone = $request->phone;
        $obj->country = $request->country;
        $obj->address = $request->address;
        $obj->state = $request->state;
        $obj->city = $request->city;
        $obj->zip = $request->zip;
        $obj->status = $request->status;
        $obj->save();

        return redirect()->route('admin_users')->with('success', 'User is Updated Successfully');
    }

    public function user_delete($id) 
    {
         
        
        
         $total = Review::where('user_id', $id)->count();
        
        if($total > 0) {
            return redirect()->back()->with('error', 'User can not be deleted because he has some reviews');
        }
        
        
        $total1 = Message::where('user_id', $id)->count();

        if($total1 > 0) {
            return redirect()->back()->with('error', 'User can not be deleted because he has some Messages');
        }
        
        $total2 = Wishlist::where('user_id', $id)->count();

        if($total2 > 0) {
            return redirect()->back()->with('error', 'User can not be deleted because he has some wishlist');
        }
        
        $total3 = Booking::where('user_id', $id)->count();

        if($total3 > 0) {
            return redirect()->back()->with('error', 'User can not be deleted because he has some booking');
        }
        
        
       
       
        $obj = User::where('id', $id)->first();
        $photoPath = public_path('uploads/' . $obj->photo);
        

        // Supprimer seulement si ce n’est PAS un dossier
        if (!empty($obj->photo) && file_exists($photoPath) && is_file($photoPath)) {
            unlink($photoPath);
        }
        
        
        $obj->delete();

        return redirect()->route('admin_users')->with('success', 'User is Deleted Successfully');
    }

    
    
    
    
    public function message()
    {
        // message has the relation with the user
        $messages = Message::with('user')->get();
        return view('admin.user.message', compact('messages'));
    }
    public function message_detail($id)
    {
       $message_comments = MessageComment::where('message_id', $id)->orderBy('id', 'desc')->get();
      return view('admin.user.message_detail', compact('message_comments', 'id'));
    }

    public function message_submit(Request $request, $id)
    {
      $obj = new MessageComment();
      $obj->message_id = $id;
      $obj->sender_id = 1;
      $obj->type = 'Admin';
      $obj->comment = $request->comment;
      $obj->save();

       
        $message_link = route('user_message_start');
       $subject = "Admin Message";
        $message = "Please click on the following link to see the new message from the admin :<br>
        <a href='{$message_link}'>Click Here</a>";
        
        // relation with user
        $message_data = Message::with('user')->where('id', $id)->first();
        $user_email = $message_data->user->email;
        
        
         // Envoyer le mail
        \Mail::to($user_email)->send(new Websitemail($subject,$message));
      
      
      
      return redirect()->back()->with('success', 'Comment added successfully');
    }
}
