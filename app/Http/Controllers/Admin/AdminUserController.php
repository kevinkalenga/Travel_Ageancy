<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Message;
use App\Models\MessageComment;
use App\Mail\Websitemail;

class AdminUserController extends Controller
{
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
