<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Mail\Websitemail;
use Hash;

class FrontController extends Controller
{
    public function home()
    {
        return view('front.home');
    }
    public function about()
    {
        return view('front.about');
    }
    public function registration()
    {
        return view('front.registration');
    }
    public function registration_submit(Request $request)
    {
        
       // Validation des champs 
        $request->validate([
           'name' => 'required',
           'email' => 'required|email',
           'password' => 'required',
           'retype_password' => 'required|same:password',
        ]);
        
          // Génération du token AVANT l'enregistrement
        $token = hash('sha256',time());
        
        // Création du nouvel utilisateur
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->token = $token;
        $user->save();

        
       
         // Préparer le lien de vérification
       $verification_link = route('registration_verify', [
            'email' => $request->email,
            'token' => $token
        ]);

       $subject = "User Account Verification";
       $message = "Please click the following link to verify your email address:<br>
        <a href='{$verification_link}'>Verify Email</a>";
        
        
        
        \Mail::to($request->email)->send(new Websitemail($subject,$message));

         return redirect()->route('login')->with('success', 'Registration successful! Please check your email for verification.');
    }
    
    public function registration_verify($email, $token)
    {
        
          $user = User::where('token',$token)->where('email',$email)->first();
          if(!$user) {
               return redirect()->route('login');
           }
           $user->token = '';
           $user->status = 1;
           $user->update();

           return redirect()->route('login')->with('success', 'Your email is verified. You can login now.'); 
     }
    
    
    
    
    public function login()
    {
        return view('front.login');
    }
    public function forget_password()
    {
        return view('front.forget_password');
    }
}
