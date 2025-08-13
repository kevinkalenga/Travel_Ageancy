<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Mail\Websitemail;
use Hash;
use Auth;

class FrontController extends Controller
{
    // Page d'accueil
    public function home() { return view('front.home'); }
     // Page "à propos"
    public function about() { return view('front.about'); }
     // Page d'inscription
    public function registration() { return view('front.registration'); }
     // Traitement du formulaire d'inscription
    public function registration_submit(Request $request)
    {
        // Validation des champs
        $request->validate([
           'name' => 'required',
           'email' => 'required|email',
           'password' => 'required',
           'retype_password' => 'required|same:password',
        ]);
        // Génération d'un token pour la vérification par email
        $token = hash('sha256',time());

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->token = $token;
        $user->status = 0; // Utilisateur non vérifié
        $user->save();
        
        // Préparer le lien de vérification
        $verification_link = route('registration_verify', [
            'email' => $request->email,
            'token' => $token
        ]);

         // Préparer le mail
        $subject = "User Account Verification";
        $message = "Please click the following link to verify your email:<br>
        <a href='{$verification_link}'>Verify Email</a>";
        
         // Envoyer le mail
        \Mail::to($request->email)->send(new Websitemail($subject,$message));
         
        // Redirection vers login avec message de succès
        return redirect()->route('login')->with('success', 'Registration successful! Please check your email for verification.');
    }
     
    // Vérification du token et activation de l'utilisateur
    public function registration_verify($email, $token)
    {
        $user = User::where('token', $token)->where('email', $email)->first();
        if(!$user) return redirect()->route('login');
         // Activation de l'utilisateur
        $user->token = '';
        $user->status = 1;
        $user->save();

        return redirect()->route('login')->with('success', 'Your email is verified. You can login now.');
    }

    public function login() { return view('front.login'); }
    
    // Traitement du formulaire de login
    public function login_submit(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        // Tentative de login avec vérification du status
        if (Auth::attempt($credentials + ['status' => 1])) {
            $request->session()->regenerate(); // Sécurisation de la session
            return redirect()->route('user_dashboard')->with('success', 'Login successful!');
        }
         
        // Retour avec message d'erreur si login échoue
        return back()->with('error', 'Email or password is incorrect!')->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken(); // Régénérer le token CSRF
        return redirect()->route('login')->with('success', 'Logout successful!');
    }
    
      // Page "Mot de passe oublié"
    public function forget_password() { return view('front.forget_password'); }
    
}
