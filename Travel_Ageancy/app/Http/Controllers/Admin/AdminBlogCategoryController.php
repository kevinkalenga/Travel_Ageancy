<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BlogCategory;
use Illuminate\Validation\Rule;

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
        $blog_category = BlogCategory::findOrFail($id);
        return view('admin.blog_category.edit', compact('blog_category'));
    }

    public function edit_submit(Request $request, $id)
    {
        $blog_category = BlogCategory::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => [
                'required',
                'alpha_dash',
                Rule::unique('blog_categories', 'slug')->ignore($blog_category->id),
            ],
        ]);

        $blog_category->name = $request->name;
        $blog_category->slug = $request->slug;
        $blog_category->save();

        return redirect()->route('admin_blog_category_index')
                         ->with('success', 'Blog Category is Updated Successfully');
    }

    public function delete($id) 
    {
        $blog_category = BlogCategory::findOrFail($id);
        $blog_category->delete();

        return redirect()->route('admin_blog_category_index')
                         ->with('success', 'Blog Category is Deleted Successfully');
    }


}
