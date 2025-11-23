<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subscriber;
use App\Mail\Websitemail;

class AdminSubscriberController extends Controller
{
    public function subscribers()
    {
        $subscribers = Subscriber::where('status', 'active')->get(); 
        return view('admin.subscriber.index', compact('subscribers'));
    }

    public function send_email()
    {
        return view('admin.subscriber.send_email');
    }
    
    public function send_email_submit(Request $request) 
    {
        $request->validate([
            'subject' => 'required|string',
            'message' => 'required|string',
        ]);

        $subject = $request->subject;
        $message = $request->message;
         
        $all_subs = Subscriber::where('status', 'active')->take(5)->get();

         foreach($all_subs as $item){
            // changement de code une fois abonner sur mailtrap et passage smtp
             \Mail::to($item->email)->queue(new Websitemail($subject, $message));
             sleep(2); // Pause pour éviter de dépasser la limite
         }


        return redirect()->back()->with('success', 'Emails are queued successfully!');
        
        
    }
    
    
    
    public function subscriber_delete($id) 
    {
         $obj = Subscriber::where('id', $id);
         $obj->delete();
         return redirect()->back()->with('success', 'Subscriber is deleted successfully');
    }
}
