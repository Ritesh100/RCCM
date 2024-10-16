<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Company;
use App\Models\RcUsers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // Display the profile view
    public function showProfile()
    {
        $user = Auth::user();

        // Pass the user details to the profile view
        return view('admin.profile', compact('user'));
    }

    // Update the authenticated user's profile
    public function updateProfile(Request $request)
    {
        // Validate the input
        $request->validate([
            'userName' => 'required|string|max:255',
            'userEmail' => 'required|email|unique:users_tbl,userEmail,' . Auth::id(),
            'password' => 'nullable|string|min:4|confirmed',
        ]);

        // Get the currently authenticated user
        $user = Auth::user();

        // Update user details
        $user->userName = $request->userName;
        $user->userEmail = $request->userEmail;

        // Update password if provided
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        // Save the updated user data
        $user->save();

        // Redirect back with a success message
        return redirect()->route('admin.profile')->with('success', 'Profile updated successfully.');
    }
    // Display the company management view
    // In AdminController.php
    public function showCompany()
    {
        // Get all companies
        $companies = Company::all(); // Ensure this line is present
        return view('admin.company', compact('companies')); // Pass companies to view
    }


    // Show form to create a new company
    public function createCompany()
    {
        return view('admin.create_company'); // Create this view
    }

    // Store a new company
    public function storeCompany(Request $request)
    {
        // Validate the input data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:company_tbl,email',
            'password' => 'required|string|min:4',
            'address' => 'string|nullable',
            'contact' => 'string|nullable',
        ]);
    
        // Create the company record in the database
        Company::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // This should hash the password correctly
            'address' => $request->address,
            'contact' => $request->contact,
        ]);
    
        // Redirect to admin page with success message
        return redirect()->route('admin.company')->with('success', 'Company created successfully.');
    }
    
    // Show the edit form for a company
    public function editCompany($id)
    {
        $company = Company::findOrFail($id);
        return view('admin.edit_company', compact('company')); // Create this view
    }

    // Update a company
    public function updateCompany(Request $request, $id)
    {
        $company = Company::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:company_tbl,email,' . $company->id,
            'contact'=>'nullable|string',
            'password' => 'nullable|string|min:4',
        ]);

        $company->name = $request->name;
        $company->email = $request->email;
        $company->address = $request->address;
        $company->contact = $request->contact;

        if ($request->password) {
            $company->password = Hash::make($request->password);

        }

        $company->save();

        return redirect()->route('admin.company')->with('success', 'Company updated successfully.');
    }

    // Delete a company
    public function deleteCompany($id)
    {
        $company = Company::findOrFail($id);
        $company->delete();

        return redirect()->route('admin.company')->with('success', 'Company deleted successfully.');
    }

    public function showUsers()
    {
        // Get all companies
        $users = RcUsers::all(); // Ensure this line is present
        return view('admin.users', compact('users')); // Pass companies to view
    }


    // Show form to create a new company
    public function createUsers()
    {
        $companies = Company::select('name', 'email')->get();

        return view('admin.create_users', compact('companies')); // Create this view
    }

    // Store a new company
    public function storeUsers(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:rccPartner_tbl,email',
            'password' => 'required|string|min:4',
            'reportingTo' => 'string',
            'hrlyRate'=>'string',
            'address'=>'string',
            'contact'=>'nullable|string',
        ]);

        RcUsers::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'reportingTo'=> $request->reportingTo,
            'hrlyRate'=>$request->hrlyRate,
            'address'=>$request->address,
            'contact'=>$request->contact,

        ]);

        return redirect()->route('admin.users')->with('success', 'Company created successfully.');
    }

    // Show the edit form for a company
    public function editUsers($id)
    {
        $users = RcUsers::findOrFail($id);
        return view('admin.edit_users', compact('users')); // Create this view
    }

    // Update a company
    public function updateUsers(Request $request, $id)
    {
        $users = RcUsers::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:rccPartner_tbl,email,' . $users->id,
            'password' => 'nullable|string|min:4',
            'reportingTo'=> 'nullable|string',
            'address'=>'nullable|string',
            'contact'=>'nullable|string',
            'hrlyRate'=>'nullable|string',
        ]);

        $users->name = $request->name;
        $users->email = $request->email;
        $users->address =$request->address;
        $users->contact =$request->contact;
        $users->reportingTo = $request->reportingTo;
        $users->hrlyRate = $request->hrlyRate;



        if ($request->password) {
            $users->password = Hash::make($request->password);
        }

        $users->save();

        return redirect()->route('admin.users')->with('success', 'Company updated successfully.');
    }

    // Delete a company
    public function deleteUsers($id)
    {
        $users = RcUsers::findOrFail($id);
        $users->delete();

        return redirect()->route('admin.users')->with('success', 'Company deleted successfully.');
    }



}
