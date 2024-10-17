<?php

namespace App\Http\Controllers;

use App\Models\RcUsers;
use App\Models\Timesheet;
use Log;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    public function getUsers()
    {
        $company = session()->get('company');
        $company_user = $company->email;

        $users = RcUsers::where('reportingTo', $company_user)->get();
        return view('company.users', compact('users'));
    }

    public function showTimeSheet()
    {
        $company = session()->get('company');
        if ($company) {
            $company_users = RcUsers::where('reportingTo', $company->email)->get();

            $userEmails = $company_users->pluck('email')->toArray();


            $users = Timesheet::whereIn('user_email', $userEmails)->paginate(10);

            return view('company.timesheet', compact('users'));
        }

        return redirect()->route('companyLogin');
    }

    public function updateStatus(Request $request, $id)
    {
        $timesheet = Timesheet::findOrFail($id);
    
        // Get the status from the request
        $newStatus = $request->input('status');
    
        // Handle status changes
        if ($newStatus === 'approved' && $timesheet->status !== 'approved') {
            $timesheet->status = 'approved';
            $timesheet->save();
            return redirect()->back()->with('success', 'Timesheet approved successfully!');
        } elseif ($newStatus === 'pending') {
            $timesheet->status = 'pending';
            $timesheet->save();
            return redirect()->back()->with('success', 'Timesheet set to pending.');
        } elseif ($newStatus === 'deleted') {
            // Handle deletion, either soft-delete or hard-delete based on your requirements
            $timesheet->delete();
            return redirect()->back()->with('success', 'Timesheet deleted successfully!');
        }
    
        return redirect()->back()->with('error', 'Invalid status or timesheet already in the selected status.');
    }

    public function updateTimesheet(Request $request, $id)
{
    $timesheet = Timesheet::findOrFail($id);

    // Update the timesheet with the new data
    $timesheet->day = $request->input('day');
    $timesheet->user_email = $request->input('user_email');
    $timesheet->cost_center = $request->input('cost_center');
    $timesheet->date = $request->input('date');
    $timesheet->start_time = $request->input('start_time');
    $timesheet->close_time = $request->input('close_time');
    $timesheet->break_start = $request->input('break_start');
    $timesheet->break_end = $request->input('break_end');
    $timesheet->timezone = $request->input('timezone');
    $timesheet->work_time = $request->input('work_time');
    
    $timesheet->save();

    return redirect()->back()->with('success', 'Timesheet updated successfully!');
}
    
}
