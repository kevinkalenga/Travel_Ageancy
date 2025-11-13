<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TeamMember;
use Illuminate\Validation\Rule;

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

    // public function create_submit(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required',
    //         'slug' => 'required',
    //         'designation' => 'required',
    //         'email' => 'required',
    //         'phone' => 'required',
    //         'address' => 'required',
    //          'photo' => ['required','image','mimes:jpg,jpeg,png,gif','max:2048'],
    //     ]);

    //     // $finale_name = 'team_member_'.time().'.'.$request->photo->extension();
    //     // $request->photo->move(public_path('uploads'), $finale_name);

    //     $finale_name = 'team_member_' . time() . '.' . $request->file('photo')->extension();
    //     $request->file('photo')->move(public_path('uploads'), $finale_name);

    //     $obj = new TeamMember();
    //     $obj->photo = $request->finale_name;
    //     $obj->name = $request->name;
    //     $obj->slug = $request->slug;
    //     $obj->designation = $request->designation;
    //     $obj->email = $request->email;
    //     $obj->phone = $request->phone;
    //     $obj->address = $request->address;
    //     $obj->biography = $request->biography;
    //     $obj->facebook = $request->facebook;
    //     $obj->twitter = $request->twitter;
    //     $obj->linkedin = $request->linkedin;
    //     $obj->instagram = $request->instagram;
       
    //     $obj->save();

    //     return redirect()->route('admin_team_member_index')->with('success', 'Team Member is Created Successfully');
    // }

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

    $finale_name = 'team_member_' . time() . '.' . $request->file('photo')->extension();

    // Save image in public/uploads/team_members/
    $request->file('photo')->move(public_path('uploads/'), $finale_name);

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
        $team_member = TeamMember::where('id', $id)->first();
        return view('admin.team_member.edit', compact('team_member'));
    }

    // public function edit_submit(Request $request, $id)
    // {
    //     $team_member = TeamMember::where('id', $id)->first();  
        
    //     $request->validate([
    //         'name' => 'required',
    //         'slug' => [
    //             'required',
    //               Rule::unique('team_members', 'slug')->ignore($team_member->id),
    //         ],
    //         'designation' => 'required',
    //         'email' => 'required',
    //         'phone' => 'required',
    //         'address' => 'required',
           
    //     ]);

    //      if ($request->hasFile('photo')) {
    //            $request->validate([
    //               'photo' => ['image', 'mimes:jpg,jpeg,png,gif', 'max:2048'],
    //            ]);

    //          // supprimer l'ancienne photo seulement si elle existe physiquement
    //       if ($team_member->photo && file_exists(public_path('uploads/'.$team_member->photo))) {
    //        unlink(public_path('uploads/'.$team_member->photo));
    //        }

    //       $finale_name = 'team_member_' . time() . '.' . $request->file('photo')->extension();
    //       $request->file('photo')->move(public_path('uploads'), $finale_name);
    //       $team_member->photo = $finale_name; // remplace par la nouvelle
    //     }


    //     $team_member->photo = $request->finale_name;
    //     $team_member->name = $request->name;
    //     $team_member->slug = $request->slug;
    //     $team_member->designation = $request->designation;
    //      $team_member->address = $request->address;
    //     $team_member->email = $request->email;
    //     $team_member->phone = $request->phone;
    //     $team_member->biography = $request->biography;
    //     $team_member->facebook = $request->facebook;
    //     $team_member->twitter = $request->twitter;
    //     $team_member->linkedin = $request->linkedin;
    //     $team_member->instagram = $request->instagram;
       
    //     $team_member->save();

    //     return redirect()->route('admin_team_member_index')->with('success', 'Team Member is Updated Successfully');
    // }

       public function edit_submit(Request $request, $id)
{
    $team_member = TeamMember::where('id', $id)->first();

    $request->validate([
        'name' => 'required',
        'slug' => [
            'required',
            Rule::unique('team_members', 'slug')->ignore($team_member->id),
        ],
        'designation' => 'required',
        'email' => 'required',
        'phone' => 'required',
        'address' => 'required',
    ]);

    // Handle new photo upload
    if ($request->hasFile('photo')) {
        $request->validate([
            'photo' => ['image', 'mimes:jpg,jpeg,png,gif', 'max:2048'],
        ]);

        // Delete old photo if exists
        if ($team_member->photo && file_exists(public_path('uploads/'.$team_member->photo))) {
            unlink(public_path('uploads/team_members/'.$team_member->photo));
        }

        $finale_name = 'team_member_' . time() . '.' . $request->file('photo')->extension();
        $request->file('photo')->move(public_path('uploads/'), $finale_name);
        $team_member->photo = $finale_name;
    }

    // Update other fields
    $team_member->name = $request->name;
    $team_member->slug = $request->slug;
    $team_member->designation = $request->designation;
    $team_member->email = $request->email;
    $team_member->phone = $request->phone;
    $team_member->address = $request->address;
    $team_member->biography = $request->biography;
    $team_member->facebook = $request->facebook;
    $team_member->twitter = $request->twitter;
    $team_member->linkedin = $request->linkedin;
    $team_member->instagram = $request->instagram;
   
    $team_member->save();

    return redirect()->route('admin_team_member_index')->with('success', 'Team Member is Updated Successfully');
}

    
    
    
    
    
    public function delete($id) 
    {
         $team_member = TeamMember::where('id', $id)->first();

         if ($team_member->photo && file_exists(public_path('uploads/'.$team_member->photo))) {
             unlink(public_path('uploads/'.$team_member->photo));
         }

         $team_member->delete();

         return redirect()->route('admin_team_member_index')
                     ->with('success', 'Team Member is Deleted Successfully');
    }


}
