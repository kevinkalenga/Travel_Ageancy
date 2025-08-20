<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Faq;

class AdminFaqController extends Controller
{
     public function index() 
    {
        // Show the feature section in the home page
        $faqs = Faq::get();
        return view('admin.faq.index', compact('faqs'));
    }

    public function create() 
    {
        return view('admin.faq.create');
    }

    public function create_submit(Request $request)
    {
        $request->validate([
            'question' => 'required',
            'answer' => 'required',
            
            
        ]);

       

        $obj = new Faq();
        $obj->question = $request->question;
        $obj->answer = $request->answer;
        $obj->save();

        return redirect()->route('admin_faq_index')->with('success', 'FAQ is Created Successfully');
    }

    public function edit($id)
    {
        $feature = Feature::where('id', $id)->first();
        return view('admin.feature.edit', compact('feature'));
    }

    public function edit_submit(Request $request, $id)
    {
        $obj = Feature::where('id', $id)->first();  
        
        $request->validate([
            'icon' => 'required',
            'heading' => 'required',
            'description' => 'required',
           
        ]);

        $obj->icon = $request->icon;
        $obj->heading = $request->heading;
        $obj->description = $request->description;
        
        $obj->save();

        return redirect()->route('admin_feature_index')->with('success', 'Feature is Updated Successfully');
    }

    public function delete($id) 
    {
        $obj = Feature::where('id', $id)->first();
        $obj->delete();

        return redirect()->route('admin_feature_index')->with('success', 'Feature is Deleted Successfully');
    }

}
