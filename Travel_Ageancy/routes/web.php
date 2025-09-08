<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminSliderController;
use App\Http\Controllers\Admin\AdminWelcomeItemController;
use App\Http\Controllers\Admin\AdminFeatureController;
use App\Http\Controllers\Admin\AdminFaqController;
use App\Http\Controllers\Admin\AdminBlogCategoryController;
use App\Http\Controllers\Admin\AdminCounterItemController;
use App\Http\Controllers\Admin\AdminTestimonialController;
use App\Http\Controllers\Admin\AdminTeamMemberController;
use App\Http\Controllers\Admin\AdminPostController;
use App\Http\Controllers\Admin\AdminAmenityController;
use App\Http\Controllers\Admin\AdminDestinationController;
use App\Http\Controllers\Admin\AdminPackageController;
use App\Http\Controllers\Admin\AdminPackageItineraryController;
use App\Http\Controllers\Front\FrontController;
use App\Http\Controllers\User\UserController;



// Pages publiques
Route::get('/', [FrontController::class, 'home'])->name('home');
Route::get('/about', [FrontController::class, 'about'])->name('about');
Route::get('/team-members', [FrontController::class, 'team_members'])->name('team_members');
Route::get('/team-member/{slug}', [FrontController::class, 'team_member'])->name('team_member');
Route::get('/registration', [FrontController::class, 'registration'])->name('registration');
Route::post('/registration', [FrontController::class, 'registration_submit'])->name('registration_submit');
Route::get('/registration-verify/{email}/{token}', [FrontController::class, 'registration_verify'])->name('registration_verify');
Route::get('/login', [FrontController::class, 'login'])->name('login');
Route::post('/login', [FrontController::class, 'login_submit'])->name('login_submit');
Route::get('/forget-password', [FrontController::class, 'forget_password'])->name('forget_password');
Route::get('/reset-password/{token}/{email}', [FrontController::class, 'reset_password'])->name('reset_password');
Route::post('/reset-password/{token}/{email}', [FrontController::class, 'reset_password_submit'])->name('reset_password_submit');
Route::get('/logout', [FrontController::class, 'logout'])->name('logout'); // logout user
Route::post('/forget-password', [FrontController::class, 'forget_password_submit'])->name('forget_password_submit');
Route::get('/faq', [FrontController::class, 'faq'])->name('faq');
Route::get('/blog', [FrontController::class, 'blog'])->name('blog');
Route::get('/post/{slug}', [FrontController::class, 'post'])->name('post');
Route::get('/category/{slug}', [FrontController::class, 'category'])->name('category');
Route::get('/destinations', [FrontController::class, 'destinations'])->name('destinations');
Route::get('/destination/{slug}', [FrontController::class, 'destination'])->name('destination');
Route::get('/package/{slug}', [FrontController::class, 'package'])->name('package');



// Dashboard utilisateur (auth middleware pour guard web)
Route::middleware('auth')->prefix('user')->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('user_dashboard');
    Route::get('/profile', [UserController::class, 'profile'])->name('user_profile');
    Route::post('/profile', [UserController::class, 'profile_submit'])->name('user_profile_submit');
});

// Admin routes
Route::middleware('admin')->prefix('admin')->group(function () {
    // Dashboard section
    Route::get('/dashboard', [AdminDashboardController::class, 'dashboard'])->name('admin_dashboard');
    // Profile section
    Route::get('/profile', [AdminAuthController::class, 'profile'])->name('admin_profile');
    Route::post('/profile', [AdminAuthController::class, 'profile_submit'])->name('admin_profile_submit');
    // Slider section
    Route::get('/slider/index', [AdminSliderController::class, 'index'])->name('admin_slider_index');
    Route::get('/slider/create', [AdminSliderController::class, 'create'])->name('admin_slider_create');
    Route::post('/slider/create', [AdminSliderController::class, 'create_submit'])->name('admin_slider_create_submit');
    Route::get('/slider/edit/{id}', [AdminSliderController::class, 'edit'])->name('admin_slider_edit');
    Route::post('/slider/edit/{id}', [AdminSliderController::class, 'edit_submit'])->name('admin_slider_edit_submit');
    Route::get('/slider/delete/{id}', [AdminSliderController::class, 'delete'])->name('admin_slider_delete');
    // Welcome section
    Route::get('/welcome-item/index', [AdminWelcomeItemController::class, 'index'])->name('admin_welcome_item_index');
    Route::post('/welcome-item/update', [AdminWelcomeItemController::class, 'update'])->name('admin_welcome_item_update');
     
    // Feature section
    Route::get('/feature/index', [AdminFeatureController::class, 'index'])->name('admin_feature_index');
    Route::get('/feature/create', [AdminFeatureController::class, 'create'])->name('admin_feature_create');
    Route::post('/feature/create', [AdminFeatureController::class, 'create_submit'])->name('admin_feature_create_submit');
    Route::get('/feature/edit/{id}', [AdminFeatureController::class, 'edit'])->name('admin_feature_edit');
    Route::post('/feature/edit/{id}', [AdminFeatureController::class, 'edit_submit'])->name('admin_feature_edit_submit');
    Route::get('/feature/delete/{id}', [AdminFeatureController::class, 'delete'])->name('admin_feature_delete');
    // Amenity section
    Route::get('/amenity/index', [AdminAmenityController::class, 'index'])->name('admin_amenity_index');
    Route::get('/amenity/create', [AdminAmenityController::class, 'create'])->name('admin_amenity_create');
    Route::post('/amenity/create', [AdminAmenityController::class, 'create_submit'])->name('admin_amenity_create_submit');
    Route::get('/amenity/edit/{id}', [AdminAmenityController::class, 'edit'])->name('admin_amenity_edit');
    Route::post('/amenity/edit/{id}', [AdminAmenityController::class, 'edit_submit'])->name('admin_amenity_edit_submit');
    Route::get('/amenity/delete/{id}', [AdminAmenityController::class, 'delete'])->name('admin_amenity_delete');

    // Counter section
    Route::get('/counter-item/index', [AdminCounterItemController::class, 'index'])->name('admin_counter_item_index');
    Route::post('/counter-item/update', [AdminCounterItemController::class, 'update'])->name('admin_counter_item_update');

    // Testimonial section
    Route::get('/testimonial/index', [AdminTestimonialController::class, 'index'])->name('admin_testimonial_index');
    Route::get('/testimonial/create', [AdminTestimonialController::class, 'create'])->name('admin_testimonial_create');
    Route::post('/testimonial/create', [AdminTestimonialController::class, 'create_submit'])->name('admin_testimonial_create_submit');
    Route::get('/testimonial/edit/{id}', [AdminTestimonialController::class, 'edit'])->name('admin_testimonial_edit');
    Route::post('/testimonial/edit/{id}', [AdminTestimonialController::class, 'edit_submit'])->name('admin_testimonial_edit_submit');
    Route::get('/testimonial/delete/{id}', [AdminTestimonialController::class, 'delete'])->name('admin_testimonial_delete');
    // Post section
    Route::get('/post/index', [AdminPostController::class, 'index'])->name('admin_post_index');
    Route::get('/post/create', [AdminPostController::class, 'create'])->name('admin_post_create');
    Route::post('/post/create', [AdminPostController::class, 'create_submit'])->name('admin_post_create_submit');
    Route::get('/post/edit/{id}', [AdminPostController::class, 'edit'])->name('admin_post_edit');
    Route::post('/post/edit/{id}', [AdminPostController::class, 'edit_submit'])->name('admin_post_edit_submit');
    Route::get('/post/delete/{id}', [AdminPostController::class, 'delete'])->name('admin_post_delete');
    // Destination section
    Route::get('/destination/index', [AdminDestinationController::class, 'index'])->name('admin_destination_index');
    Route::get('/destination/create', [AdminDestinationController::class, 'create'])->name('admin_destination_create');
    Route::post('/destination/create', [AdminDestinationController::class, 'create_submit'])->name('admin_destination_create_submit');
    Route::get('/destination/edit/{id}', [AdminDestinationController::class, 'edit'])->name('admin_destination_edit');
    Route::post('/destination/edit/{id}', [AdminDestinationController::class, 'edit_submit'])->name('admin_destination_edit_submit');
    Route::get('/destination/delete/{id}', [AdminDestinationController::class, 'delete'])->name('admin_destination_delete');
    Route::get('/destination-photos/{id}', [AdminDestinationController::class, 'destination_photos'])->name('admin_destination_photos');
    Route::post('/destination-photo-submit/{id}', [AdminDestinationController::class, 'destination_photo_submit'])->name('admin_destination_photo_submit');
    Route::get('/destination-photo-delete/{id}', [AdminDestinationController::class, 'destination_photo_delete'])->name('admin_destination_photo_delete');
    Route::get('/destination-videos/{id}', [AdminDestinationController::class, 'destination_videos'])->name('admin_destination_videos');
    Route::post('/destination-video-submit/{id}', [AdminDestinationController::class, 'destination_video_submit'])->name('admin_destination_video_submit');
    Route::get('/destination-video-delete/{id}', [AdminDestinationController::class, 'destination_video_delete'])->name('admin_destination_video_delete');

    // Package Amenity section 
    Route::get('/package-amenities/{id}', [AdminPackageController::class, 'package_amenities'])->name('admin_package_amenities');
    Route::post('/package-amenity-submit/{id}', [AdminPackageController::class, 'package_amenity_submit'])->name('admin_package_amenity_submit');
    Route::get('/package-amenity-delete/{id}', [AdminPackageController::class, 'package_amenity_delete'])->name('admin_package_amenity_delete');
    // Package Itinerary section (géré par AdminPackageController)
    Route::get('/package-itineraries/{id}', [AdminPackageController::class, 'package_itineraries'])->name('admin_package_itineraries');
    Route::post('/package-itinerary-submit/{id}', [AdminPackageController::class, 'package_itinerary_submit'])->name('admin_package_itinerary_submit');
    Route::get('/package-itinerary-delete/{id}', [AdminPackageController::class, 'package_itinerary_delete'])->name('admin_package_itinerary_delete');
    // Package section
    Route::get('/package/index', [AdminPackageController::class, 'index'])->name('admin_package_index');
    Route::get('/package/create', [AdminPackageController::class, 'create'])->name('admin_package_create');
    Route::post('/package/create', [AdminPackageController::class, 'create_submit'])->name('admin_package_create_submit');
    Route::get('/package/edit/{id}', [AdminPackageController::class, 'edit'])->name('admin_package_edit');
    Route::post('/package/edit/{id}', [AdminPackageController::class, 'edit_submit'])->name('admin_package_edit_submit');
    Route::get('/package/delete/{id}', [AdminPackageController::class, 'delete'])->name('admin_package_delete');
    Route::get('/package/photos/{id}', [AdminPackageController::class, 'photos'])->name('admin_package_photos');
    Route::post('/package/photos/{id}', [AdminPackageController::class, 'photos_submit'])->name('admin_package_photos_submit');
    Route::get('/package/photo/delete/{id}', [AdminPackageController::class, 'photo_delete'])->name('admin_package_photo_delete');

    Route::get('/package/videos/{id}', [AdminPackageController::class, 'videos'])->name('admin_package_videos');
    Route::post('/package/videos/{id}', [AdminPackageController::class, 'videos_submit'])->name('admin_package_videos_submit');
    Route::get('/package/video/delete/{id}', [AdminPackageController::class, 'video_delete'])->name('admin_package_video_delete');
    // Team Member section
    Route::get('/team-member/index', [AdminTeamMemberController::class, 'index'])->name('admin_team_member_index');
    Route::get('/team-member/create', [AdminTeamMemberController::class, 'create'])->name('admin_team_member_create');
    Route::post('/team-member/create', [AdminTeamMemberController::class, 'create_submit'])->name('admin_team_member_create_submit');
    Route::get('/team-member/edit/{id}', [AdminTeamMemberController::class, 'edit'])->name('admin_team_member_edit');
    Route::post('/team-member/edit/{id}', [AdminTeamMemberController::class, 'edit_submit'])->name('admin_team_member_edit_submit');
    Route::get('/team-member/delete/{id}', [AdminTeamMemberController::class, 'delete'])->name('admin_team_member_delete');

    // Faq section
    Route::get('/faq/index', [AdminFaqController::class, 'index'])->name('admin_faq_index');
    Route::get('/faq/create', [AdminFaqController::class, 'create'])->name('admin_faq_create');
    Route::post('/faq/create', [AdminFaqController::class, 'create_submit'])->name('admin_faq_create_submit');
    Route::get('/faq/edit/{id}', [AdminFaqController::class, 'edit'])->name('admin_faq_edit');
    Route::post('/faq/edit/{id}', [AdminFaqController::class, 'edit_submit'])->name('admin_faq_edit_submit');
    Route::get('/faq/delete/{id}', [AdminFaqController::class, 'delete'])->name('admin_faq_delete');
    // Blog Category section
    Route::get('/blog_category/index', [AdminBlogCategoryController::class, 'index'])->name('admin_blog_category_index');
    Route::get('/blog_category/create', [AdminBlogCategoryController::class, 'create'])->name('admin_blog_category_create');
    Route::post('/blog_category/create', [AdminBlogCategoryController::class, 'create_submit'])->name('admin_blog_category_create_submit');
    Route::get('/blog_category/edit/{id}', [AdminBlogCategoryController::class, 'edit'])->name('admin_blog_category_edit');
    Route::post('/blog_category/edit/{id}', [AdminBlogCategoryController::class, 'edit_submit'])->name('admin_blog_category_edit_submit');
    Route::get('/blog_category/delete/{id}', [AdminBlogCategoryController::class, 'delete'])->name('admin_blog_category_delete');
});

Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'login'])->name('admin_login');
    Route::post('/login', [AdminAuthController::class, 'login_submit'])->name('admin_login_submit');
    Route::get('/logout', [AdminAuthController::class, 'logout'])->name('admin_logout');
    Route::get('/forget-password', [AdminAuthController::class, 'forget_password'])->name('admin_forget_password');
    Route::post('/forget-password', [AdminAuthController::class, 'forget_password_submit'])->name('admin_forget_password_submit');
    Route::get('/reset-password/{token}/{email}', [AdminAuthController::class, 'reset_password'])->name('admin_reset_password');
    Route::post('/reset-password/{token}/{email}', [AdminAuthController::class, 'reset_password_submit'])->name('admin_reset_password_submit');
});
