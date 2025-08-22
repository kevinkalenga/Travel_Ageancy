<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\BlogCategory;

class AdminPostController extends Controller
{
     public function index() 
    {
        $posts = Post::with('blog_category')->get();
        return view('admin.post.index', compact('posts'));
    }

    public function create() 
    {
        $categories = BlogCategory::get();
        return view('admin.post.create', compact('categories'));
    }

    public function create_submit(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'slug' => 'required|alpha_dash|unique:posts',
            'description' => 'required',
            'short_description' => 'required',
            'photo' => ['image', 'mimes:jpg,jpeg,png,gif', 'max:2048'],
        ]);

        $finale_name = 'post_'.time().'.'.$request->photo->extension();
        $request->photo->move(public_path('uploads'), $finale_name);

        $obj = new Post();
        $obj->blog_category_id = $request->blog_category_id;
        $obj->title = $request->title;
        $obj->slug = $request->slug;
        $obj->description = $request->description;
        $obj->short_description = $request->short_description;
        $obj->photo = $finale_name;
        $obj->save();

        return redirect()->route('admin_post_index')->with('success', 'Post is Created Successfully');
    }

    public function edit($id)
    {
        $testimonial = Testimonial::where('id', $id)->first();
        return view('admin.testimonial.edit', compact('testimonial'));
    }

    public function edit_submit(Request $request, $id)
    {
        $testimonial = Testimonial::where('id', $id)->first();  
        
        $request->validate([
            'name' => 'required',
            'designation' => 'required',
            'comment' => 'required',
           
        ]);

        if($request->hasFile('photo')) 
        {
            $request->validate([
           
                'photo' => ['image', 'mimes:jpg,jpeg,png,gif', 'max:2048'],
            ]);

            unlink(public_path('uploads/'.$testimonial->photo));

            $finale_name = 'testimonial_'.time().'.'.$request->photo->extension();
            $request->photo->move(public_path('uploads'), $finale_name);
            $testimonial->photo = $finale_name;
        }

        $testimonial->name = $request->name;
        $testimonial->designation = $request->designation;
        $testimonial->comment = $request->comment;
       
        $testimonial->save();

        return redirect()->route('admin_testimonial_index')->with('success', 'Testimonial is Updated Successfully');
    }

    public function delete($id) 
    {
        $testimonial = Testimonial::where('id', $id)->first();
        unlink(public_path('uploads/'.$testimonial->photo));
        $testimonial->delete();

        return redirect()->route('admin_testimonial_index')->with('success', 'Testimonial is Deleted Successfully');
    }

}
