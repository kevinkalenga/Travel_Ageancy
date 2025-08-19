<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TeamMember;

class AdminTeamMemberController extends Controller
{
    public function index() 
    {
        $team_members = TeamMember::get();
        return view('admin.team_member.index', compact('team_members'));
    }

    public function create() 
    {
        return view('admin.team_member.create');
    }

    public function create_submit(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required',
            'designation' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'address' => 'required',
             'photo' => ['required','image','mimes:jpg,jpeg,png,gif','max:2048'],
        ]);

        // $finale_name = 'team_member_'.time().'.'.$request->photo->extension();
        // $request->photo->move(public_path('uploads'), $finale_name);

        $finale_name = 'team_member_' . time() . '.' . $request->file('photo')->extension();
        $request->file('photo')->move(public_path('uploads'), $finale_name);

        $obj = new TeamMember();
        $obj->photo = $finale_name;
        $obj->name = $request->name;
        $obj->slug = $request->slug;
        $obj->designation = $request->designation;
        $obj->email = $request->email;
        $obj->phone = $request->phone;
        $obj->address = $request->address;
        $obj->biography = $request->biography;
        $obj->facebook = $request->facebook;
        $obj->twitter = $request->twitter;
        $obj->linkedin = $request->linkedin;
        $obj->instagram = $request->instagram;
       
        $obj->save();

        return redirect()->route('admin_team_member_index')->with('success', 'Team Member is Created Successfully');
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
