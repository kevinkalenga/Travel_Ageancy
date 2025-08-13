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
    
   

//     public function login_submit(Request $request)
// {
//     $request->validate([
//         'email' => 'required|email',
//         'password' => 'required',
//     ]);

//     $credentials = $request->only('email', 'password');

//     // Test de debug
//     if (!Auth::attempt($credentials + ['status' => 1])) {
//         // On regarde pourquoi ça échoue
//         $user = User::where('email', $request->email)->first();
        
//         if (!$user) {
//             dd('Utilisateur introuvable');
//         }

//         if (!Hash::check($request->password, $user->password)) {
//             dd('Mot de passe incorrect');
//         }

//         if ($user->status != 1) {
//             dd('Compte inactif');
//         }

//         dd('Auth::attempt a échoué pour une autre raison');
//     }

//     // Si login OK
//     $request->session()->regenerate();
//     return redirect()->route('user_dashboard')->with('success', 'Login successful!');
// }

   public function login_submit(Request $request)
{
    // 1️⃣ Validation des données
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    // 2️⃣ Préparer les identifiants
    $credentials = $request->only('email', 'password');

    // 3️⃣ Tenter la connexion en vérifiant que le compte est actif
    if (Auth::attempt($credentials + ['status' => 1])) {
        $request->session()->regenerate(); // Sécurise la session
        return redirect()->route('user_dashboard')->with('success', 'Login successful!');
    }

    // 4️⃣ Retour avec erreur si la connexion échoue
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
    
    public function forget_password_submit(Request $request)
    {
         // Validation du champ email
       $request->validate([
        'email' => ['required', 'email'],
       ]);
        
        // Vérifier si l'utilisateur existe
        $user = User::where('email',$request->email)->first();
        if(!$user) {
          return redirect()->back()->with('error','Email is not found');
        }
       // Générer un token pour le reset du mot de passe
      $token = hash('sha256',time());
      $user->token = $token;
      $user->save();
      
       // Préparer le lien de reset
      $reset_link = route('reset_password', ['token' => $token, 'email' => $request-> email]);
      $subject = "Password Reset";
      $message = "To reset password, please click on the link below:<br>";
      $message .= "<a href='".$reset_link."'>Click Here</a>";
      
      // Envoyer le mail
      \Mail::to($request->email)->send(new Websitemail($subject,$message));
       
       // Rediriger avec message de succès
       return redirect()->back()->with('success','We have sent a password reset link to your email. Please check your email. If you do not find the email in your inbox, please check your spam folder.');
    }

    
    
    
    public function reset_password($token, $email) 
    {
         $user = User::where('email',$email)->where('token',$token)->first();
         if(!$user) {
             return redirect()->route('login')->with('error','Token or email is not correct');
         }
          return view('front.reset-password', compact('token','email'));
        
       
    }

    public function reset_password_submit(Request $request, $token, $email)
   {
    $request->validate([
        'password' => ['required'],
        'retype_password' => ['required', 'same:password'],
    ]);

    $user = User::where('email', $email)
                ->where('token', $token)
                ->first();

    if (!$user) {
        return redirect()->route('login')->with('error', 'Invalid token or email.');
    }

    $user->password = \Hash::make($request->password);
    $user->token = '';
    $user->status = 1; // 🔹 On active le compte
    $user->save();

    return redirect()->route('login')->with('success', 'Password reset is successful. You can login now.');
  }



    
}
