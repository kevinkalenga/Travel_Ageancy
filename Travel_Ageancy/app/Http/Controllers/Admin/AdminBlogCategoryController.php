<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BlogCategory;

class AdminBlogCategoryController extends Controller
{
    public function index() 
    {
        // Show the feature section in the home page
        $blog_categories = BlogCategory::get();
        return view('admin.blog_category.index', compact('blog_categories'));
    }

    public function create() 
    {
        return view('admin.blog_category.create');
    }

    public function create_submit(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|alpha_dash|unique:blog_categories',
            
            
        ]);

       

        $obj = new BlogCategory();
        $obj->name = $request->name;
        $obj->slug = $request->slug;
        $obj->save();

        return redirect()->route('admin_blog_category_index')->with('success', 'Blog Category is Created Successfully');
    }

    public function edit($id)
    {
        $faq = Faq::where('id', $id)->first();
        return view('admin.faq.edit', compact('faq'));
    }

    public function edit_submit(Request $request, $id)
    {
        $obj = Faq::where('id', $id)->first();  
        
        $request->validate([
            'question' => 'required',
            'answer' => 'required',
           
        ]);

        
        $obj->question = $request->question;
        $obj->answer = $request->answer;
        
        $obj->save();

        return redirect()->route('admin_faq_index')->with('success', 'FAQ is Updated Successfully');
    }

    public function delete($id) 
    {
        $faq = Faq::where('id', $id)->first();
        $faq->delete();

        return redirect()->route('admin_faq_index')->with('success', 'FAQ is Deleted Successfully');
    }

}
