<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validate input
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);
    
        // Check if the user exists
        $user = User::where('userEmail', $request->email)->first();
    
        // Verify the password and login
        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user);
    
            // Redirect based on user role
            switch ($user->role) {
                case 'admin':
                    return redirect()->route('admin.dashboard');
                case 'company':
                    return redirect()->route('company.dashboard');
                case 'RC':
                    return redirect()->route('rc.dashboard');
                default:
                    return redirect()->route('default.dashboard'); // Optional: Fallback dashboard
            }
        }
    
        // If credentials are invalid
        return redirect()->back()->with('error', 'Invalid credentials!');
    }
   
    public function logout(Request $request)
    {
        // Log the user out
        Auth::logout();

        // Invalidate the session and regenerate CSRF token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect to login page with a success message
        return redirect()->route('login')->with('success', 'You have been logged out successfully.');
    }

    //company
    public function showCompanyLoginForm()
    {
        return view('auth.company_login');
    }
    public function Companylogin(Request $request)
    {
        // Validate input
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);
    
        // Check if the user exists
        $company = Company::where('email', $request->email)->first();
    
        // Verify the password and login
        if ($company && Hash::check($request->password, $company->password)) {
            $request->session()->put('company', $company);

            return redirect()->route('company.dashboard');

        }
    
        // If credentials are invalid
        return redirect()->back()->with('error', 'Invalid credentials!');
    }
    public function companyLogout(Request $request)
    {
        // Log the user out
        Auth::logout();

        // Invalidate the session and regenerate CSRF token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect to login page with a success message
        return redirect()->route('companyLogin')->with('success', 'You have been logged out successfully.');
    }
    
}
