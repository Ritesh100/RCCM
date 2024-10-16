<?php

namespace App\Http\Controllers;

use Log;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CompanyController extends Controller
{
    //
    public function updateProfile(Request $request)
    {
            $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:company_tbl,email,' . session('company')->id,
            'address' => 'nullable|string|max:255',
            'contact' => 'nullable|string|max:15',
            'password' => 'nullable|string|min:6|confirmed',
        ]);
    
        $company = Company::find(session('company')->id);
    
        if (!$company) {
            return redirect()->back()->with('error', 'Company not found!');
        }
    
        // Update company details
        $company->name = $request->name;
        $company->email = $request->email;
        $company->address = $request->address;
        $company->contact = $request->contact;
    
        if ($request->filled('password')) {
            $company->password = Hash::make($request->password);
        }
            $company->save();
    
        $request->session()->put('company', $company);
            return redirect()->route('company.dashboard')->with('success', 'Profile updated successfully!');
    }
    

public function editProfile()
{
    $company = session('company');
    if (!$company) {
        return redirect()->route('auth.company_login')->with('error', 'You must be logged in to access this page.');
    }

    // Pass the company data to the view
    return view('company.profile', compact('company'));
}

}
