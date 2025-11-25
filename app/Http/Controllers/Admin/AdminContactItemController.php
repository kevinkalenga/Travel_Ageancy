<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactItem;

class AdminContactItemController extends Controller
{
    public function index() 
    {
        // I'm going to get the data from $welcome_item
        $contact_item = ContactItem::where('id', 1)->first();
        return view('admin.contact_item.index', compact('contact_item'));
    }
     public function update(Request $request)
    {
        $obj = ContactItem::where('id', 1)->first();  
        
        $obj->map_code = $request->map_code;
        $obj->save();

        return redirect()->back()->with('success', 'ContactItem is Updated Successfully');
    }
}
