<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\WelcomeItem;
use App\Mail\Websitemail;
use Hash;
use Auth;
use App\Models\Slider;
use App\Models\Feature;
use App\Models\CounterItem;
use App\Models\Testimonial;
use App\Models\TeamMember;
use App\Models\Faq;
use App\Models\Post;
use App\Models\Destination;
use App\Models\Package;
use App\Models\DestinationPhoto;
use App\Models\DestinationVideo;
use App\Models\BlogCategory;
use App\Models\PackageAmenity;
use App\Models\PackageItinerary;
use App\Models\PackagePhoto;
use App\Models\PackageFaqs;
use App\Models\PackageVideo;
use App\Models\Amenity;
use App\Models\Tour;
use App\Models\Booking;
use App\Models\Admin;
use App\Models\Review;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Stripe\StripeClient;


class FrontController extends Controller
{
    // Page d'accueil
    public function home() 
    { 
        $sliders = Slider::get();
        $welcome_item = WelcomeItem::where('id', 1)->first();
        $features = Feature::get();
        $destinations = Destination::orderBy('view_count', 'desc')->get()->take(8);
        $posts = Post::with('blog_category')->orderBy('id', 'desc')->get()->take(3);
        $testimonials = Testimonial::get();

        $destinations = Destination::orderBy('name', 'asc')->get();
        $packages = Package::with(['destination', 'package_amenities', 'package_itineraries', 'tours', 'reviews'])
        ->orderBy('id', 'desc')->get()->take(3);
        
        // pass to the front
        return view('front.home', compact('sliders', 'welcome_item', 'features', 'testimonials', 'posts', 'destinations', 'packages')); 
    }
     // Page "à propos"
    public function about() 
    { 
        $welcome_item = WelcomeItem::where('id', 1)->first();
        $features = Feature::get();
        $counter_item = CounterItem::where('id', 1)->first();;
        return view('front.about', compact('welcome_item', 'features', 'counter_item')); 
    }

    public function team_members() 
    {
        $team_members = TeamMember::paginate(4);
       return view('front.team_members', compact('team_members')); 
    }

    public function team_member($slug)
    {
          $team_member = TeamMember::where('slug', $slug)->first();
           
           if (!$team_member) {
             abort(404, 'Team member not found');
           }
          
          return view('front.team_member', compact('team_member')); 
    }

    public function faq() 
    {
        $faqs = Faq::get();
        return view('front.faq', compact('faqs'));
    }

    public function blog()
    {
        $posts = Post::with('blog_category')->paginate(9);
        return view('front.blog', compact('posts'));
    }
    public function post($slug)
    {
        $categories = BlogCategory::orderBy('name', 'asc')->get();
        $post = Post::with('blog_category')->where('slug', $slug)->first();
        $latest_posts = Post::with('blog_category')->orderBy('id', 'desc')->get()->take(5);
        return view('front.post', compact('post', 'categories', 'latest_posts'));
    }
    public function category($slug)
    {
        $category = BlogCategory::where('slug', $slug)->first();
        $posts = Post::with('blog_category')
        ->where('blog_category_id', $category->id)->orderBy('id', 'desc')->paginate(9);
        return view('front.category', compact('posts', 'category'));
    }
    public function destinations()
    {
        $destinations = Destination::orderBy('id', 'asc')->paginate(20);
       
        return view('front.destinations', compact('destinations'));
    }

    public function destination($slug)
    {
        $destination = Destination::where('slug', $slug)->first();
        $destination->view_count = $destination->view_count + 1;
        $destination->update();

        $destination_photos = DestinationPhoto::where('destination_id', $destination->id)->get();
        $destination_videos = DestinationVideo::where('destination_id', $destination->id)->get();

        $packages = Package::with(['destination', 'package_amenities', 'package_itineraries', 'tours', 'reviews'])
        ->orderBy('id', 'desc')->where('destination_id', $destination->id)->get()->take(3);

         return view('front.destination', compact('destination', 'destination_photos', 'destination_videos', 'packages'));
    }

    public function package($slug)
    {
        $package = Package::with('destination')->where('slug', $slug)->firstOrFail();
        $package_amenities_include = PackageAmenity::with('amenity')->where('package_id', $package->id)->where('type', 'Include')->get();
        $package_amenities_exclude = PackageAmenity::with('amenity')->where('package_id', $package->id)->where('type', 'Exclude')->get();
        $package_itineraries = PackageItinerary::where('package_id', $package->id)->get();
        $package_photos = PackagePhoto::where('package_id', $package->id)->get();
        $package_videos = PackageVideo::where('package_id', $package->id)->get();
        $package_faqs = PackageFaqs::where('package_id', $package->id)->get();
        $tours = Tour::where('package_id', $package->id)->get();
        $reviews = Review::with('user')->where('package_id', $package->id)->get();
        return view('front.package', compact('package', 'package_amenities_include', 'package_amenities_exclude', 'package_itineraries', 'package_photos', 'package_videos', 'package_faqs', 'tours', 'reviews'));
    }

    
     public function enquery_form_submit(Request $request, $id)
   {
    $package = Package::find($id);
    $admin = Admin::find(1);

    $request->validate([
        'name' => 'required',
        'email' => 'required|email',
        'phone' => 'required',
        'message' => 'required',
    ]);

    $subject = "Enquiry about: ".$package->name;
    $message  = "<b>Name:</b> ".$request->name."<br>";
    $message .= "<b>Email:</b> ".$request->email."<br>";
    $message .= "<b>Phone:</b> ".$request->phone."<br>";
    $message .= "<b>Message:</b> ".nl2br($request->message)."<br>";

    \Mail::html($message, function ($m) use ($subject, $admin) {
        $m->to($admin->email)
          ->subject($subject);
    });

    return redirect()->back()->with('success', 'Your enquiry is submitted successfully. We will contact you soon.');
}

     // Page d'inscription
    public function registration() { return view('front.registration'); }
     // Traitement du formulaire d'inscription
    public function registration_submit(Request $request)
    {
        // Validation des champs
        $request->validate([
           'name' => 'required',
           'email' => 'required|email|unique:users,email,' . Auth::id(),
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
    
   



   public function login_submit(Request $request)
{
    // 1️ Validation des données
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    // 2️ Préparer les identifiants
    $credentials = $request->only('email', 'password');

    // 3️ Tenter la connexion en vérifiant que le compte est actif
    if (Auth::attempt($credentials + ['status' => 1])) {
        $request->session()->regenerate(); // Sécurise la session
        return redirect()->route('user_dashboard')->with('success', 'Login successful!');
    }

    // 4️ Retour avec erreur si la connexion échoue
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


// Méthode de paiement (PayPal & Stripe)
   

  public function payment(Request $request)
  {
    if(!$request->tour_id) {
        return redirect()->back()->with('error', 'Please select a tour first!');
    }

    $tour = Tour::findOrFail($request->tour_id);
    $total_allowed_seats = $tour->total_seat;

     // ✅ Vérification du nombre de places si limité
    if ($total_allowed_seats != -1) {
        $total_booked_seats = Booking::where('tour_id', $tour->id)
            ->where('package_id', $request->package_id)
            ->sum('total_person');

        $remaining_seats = $total_allowed_seats - $total_booked_seats;

        if ($request->total_person > $remaining_seats) {
            return redirect()->back()->with('error', 'Sorry! Only '.$remaining_seats.' seats are available for this tour!');
        }
    }

       $user_id = Auth::id();
       $package = Package::findOrFail($request->package_id);
       $total_price = $request->ticket_price * $request->total_person;

     // ✅ Paiement via PayPal
    if ($request->payment_method === 'Paypal') {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();

        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('paypal_success'),
                "cancel_url" => route('paypal_cancel'),
            ],
            "purchase_units" => [[
                "amount" => [
                    "currency_code" => "USD",
                    "value" => $total_price,
                ],
                "description" => $package->name,
            ]],
        ]);

        if (isset($response['id'])) {
            foreach ($response['links'] as $link) {
                if ($link['rel'] === 'approve') {
                    session([
                        'paypal_order_id' => $response['id'],
                        'package_id' => $request->package_id,
                        'tour_id' => $request->tour_id,
                        'total_person' => $request->total_person,
                        'user_id' => $user_id,
                    ]);
                    return redirect()->away($link['href']);
                }
            }
        }

        return redirect()->route('paypal_cancel')->with('error', 'The PayPal payment could not be processed.');
    }

    // ✅ Paiement via Stripe
    if ($request->payment_method === 'Stripe') {
        $stripe = new StripeClient(config('services.stripe.secret'));
        $session = $stripe->checkout->sessions->create([
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => ['name' => $package->name],
                    'unit_amount' => $total_price * 100, // Stripe attend des cents
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('stripe_success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('stripe_cancel'),
            'metadata' => [
            'tour_id'      => $request->tour_id,
            'package_id'   => $request->package_id,
            'user_id'      => $user_id,
            'total_person' => $request->total_person,
            'price'        => $total_price,
      ],
        ]);

        if (!empty($session->url)) {
            session([
                'package_id' => $request->package_id,
                'tour_id' => $request->tour_id,
                'total_person' => $request->total_person,
                'user_id' => $user_id,
                'price' => $total_price,
            ]);
            return redirect($session->url);
        }

        return redirect()->back()->with('error', 'Unable to create Stripe payment session.');
    }

    

     // ✅ Paiement via Cach

    if($request->payment_method === 'Cash') {
        $booking = new Booking();
        $booking->tour_id = $request->tour_id;
        $booking->package_id = $request->package_id;
        $booking->user_id = Auth::id();
        $booking->total_person = $request->total_person;
        $booking->paid_amount = 0;
        $booking->paid_method = 'Cash';
        $booking->paid_status = 'PENDING'; // 🔥 Paiement en attente
        $booking->invoice_no = time();
        $booking->save();

         return redirect()->route('user_dashboard')
        ->with('success', 'Your reservation has been registered. Please pay at the agency.');
    }

     return redirect()->back()->with('error', 'Invalid payment method selected.');
   }

 


    // PayPal Success
    public function paypal_success(Request $request)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();
        $response = $provider->capturePaymentOrder($request->token);

        if (isset($response['status']) && $response['status'] === 'COMPLETED') {
            $capture = $response['purchase_units'][0]['payments']['captures'][0];

            $order = new Booking();
            $order->tour_id = session('tour_id');
            $order->package_id = session('package_id');
            $order->user_id = session('user_id');
            $order->total_person = session('total_person');
            $order->paid_amount = $capture['amount']['value'] ?? 0;
            $order->paid_method = 'PayPal';
            $order->paid_status = $capture['status'] ?? 'COMPLETED';
            $order->invoice_no = time();
            $order->save();

            return redirect()->route('user_dashboard')->with('success', 'Payment is succeful ! Your reservation has been registered.');
        }

        return redirect()->route('paypal_cancel')->with('error', 'Payment failed!.');
    }

    public function paypal_cancel()
    {
        return redirect()->back()->with('error', 'Payment cancelled!');
    }

    
     public function stripe_success(Request $request)
{
    // Vérifier que session_id existe bien
    if (!$request->has('session_id')) {
        return redirect()->route('stripe_cancel')->with('error', 'Invalid session.');
    }

    // Utiliser toujours la clé définie dans config/services.php
    $stripe = new StripeClient(config('services.stripe.secret'));

    // Récupérer la session Stripe
    $session = $stripe->checkout->sessions->retrieve($request->session_id);

    // Vérifier le statut du paiement
    if ($session->payment_status === 'paid') {
        // Créer la réservation avec les metadata
        $booking = new Booking();
        $booking->tour_id       = $session->metadata->tour_id;
        $booking->package_id    = $session->metadata->package_id;
        $booking->user_id       = $session->metadata->user_id;
        $booking->total_person  = $session->metadata->total_person;
        $booking->paid_amount   = $session->metadata->price;
        $booking->paid_method   = 'Stripe';
        $booking->paid_status   = 'COMPLETED';
        $booking->invoice_no    = time();
        $booking->save();

        return redirect()->route('user_dashboard')->with('success', 'Payment is successful! Your reservation has been registered.');
    }

    return redirect()->route('stripe_cancel')->with('error', 'Payment failed.');
}


    public function stripe_cancel()
    {
        return redirect()->back()->with('error', 'Payment cancelled!');
    }
 
    public function review_submit(Request $request)
    {
        

          //dd($request->all());
          $request->validate([
            'rating' => 'required',
            'comment' => 'required',
          ]);

          $obj = new Review;
          $obj->user_id = Auth::guard('web')->user()->id;
          $obj->package_id = $request->package_id;
          $obj->rating = $request->rating;
          $obj->comment = $request->comment;
          $obj->save();

        //   get the existing data 
        $package_data = Package::where('id', $request->package_id)->first();
        $package_data->total_rating = $package_data->total_rating + 1;
        $package_data->total_score = $package_data->total_score + $request->rating;
        

        $package_data->update();

          return redirect()->back()->with('success', 'Review is submitted successfully!');
    }

    public function packages(Request $request)
    {
        //  dd($request->all());
       $form_name = $request->name;
       $form_min_price = $request->min_price;
       $form_max_price = $request->max_price;
       $form_destination_id = $request->destination_id;
       $form_review = $request->review;
       
        $destinations = Destination::orderBy('name', 'asc')->get();
        $packages = Package::with(['destination', 'package_amenities', 'package_itineraries', 'tours', 'reviews'])
        ->orderBy('id', 'desc');

        // Search by title

        if($request->name != '') {
            $packages = $packages->where('name', 'like', '%'.$request->name.'%');
        }

        // Search by price 

        if($request->min_price != '') {
            $packages = $packages->where('price', '>=',$request->min_price);
        }
        if($request->max_price != '') {
            $packages = $packages->where('price', '<=',$request->max_price);
        }
        // Search by destination
        if($request->destination_id != '') {
            $packages = $packages->where('destination_id',$request->destination_id);
        }
        // Search by review
        if($request->review != 'all' && $request->review != null) {
            $packages = $packages->whereRaw('total_score/total_rating = ?', [$request->review]);
        }

        
        $packages = $packages->paginate(6);
        
        
        return view('front.packages', compact('destinations', 'packages',
         'form_name', 'form_min_price', 'form_max_price', 'form_destination_id', 'form_review'));
    }

    
}
