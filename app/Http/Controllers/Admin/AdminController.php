<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Company;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Payslip;
use DateTime;
use App\Models\RcUsers;
use App\Models\Document;
use App\Models\Timesheet;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

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
            'abn' => 'nullable|string',
            'address' => 'nullable|string',
            'userEmail' => 'required|email|unique:users_tbl,userEmail,' . Auth::id(),
            'password' => 'nullable|string|min:4|confirmed',
        ]);

        // Get the currently authenticated user
        $user = Auth::user();

        // Update user details
        $user->userName = $request->userName;
        $user->abn = $request->abn;
        $user->address = $request->address;

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
        return redirect()->route('admin.company');
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
            'contact' => 'nullable|string',
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
            'hrlyRate' => 'string',
            'address' => 'string',
            'contact' => 'nullable|string',
        ]);

        RcUsers::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'reportingTo' => $request->reportingTo,
            'hrlyRate' => $request->hrlyRate,
            'address' => $request->address,
            'contact' => $request->contact,

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
            'reportingTo' => 'nullable|string',
            'address' => 'nullable|string',
            'contact' => 'nullable|string',
            'hrlyRate' => 'nullable|string',
        ]);

        $users->name = $request->name;
        $users->email = $request->email;
        $users->address = $request->address;
        $users->contact = $request->contact;
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

    public function showDocument()
    {
        // Fetch all documents without filtering by reportingTo
        $documents = Document::all();

        // Return the view and pass all documents to it
        return view('admin.document', compact('documents'));
    }

    public function deleteDocument($id)
    {

        $document = Document::find($id);

        // Regular users can only delete their own documents
        $document = Document::where('id', $id)
            ->first();


        // If the document exists, proceed with the deletion
        if ($document) {
            // Optional: Delete the file from storage
            if (Storage::exists($document->path)) {
                Storage::delete($document->path);
            }

            // Delete the document record from the database
            $document->delete();

            return redirect()->back()->with('success', 'Document deleted successfully.');
        }

        // If document is not found or unauthorized access
        return redirect()->back()->with('error', 'Document not found or unauthorized.');
    }
    public function showInvoice()
    {
        return view('admin.invoice');
    }

    public function createInvoice()
    {
        $admin = User::first();
        $users = RcUsers::all();

        $invoice_number = "rcc_" . random_int(0, 999999);
        return view('admin.createInvoice', compact('admin', 'users', 'invoice_number'));
    }

    public function storeInvoice(Request $request)
{
    

    $data = $request->all();
    $filePaths = []; // Initialize an array to store file paths

    // Check if the request has files
    if ($request->hasFile('invoice_images')) {
        $uploadedFiles = $request->file('invoice_images');
        foreach ($uploadedFiles as $file) {
            // Store each file and save the path in the array
            $filePath = $file->store('invoices', 'public'); 
           
        }
    }

    
    return $data . $uploadedFiles;
}
public function showPayslips(Request $request)
{
    // Get all companies
    $companies = Company::all();
    
    // Get all users (rccPartners)
    $users = RcUsers::when($request->has('search'), function($query) use ($request) {
            return $query->where('name', 'LIKE', '%' . $request->search . '%');
        })
        ->when($request->has('company'), function($query) use ($request) {
            return $query->whereHas('company', function($q) use ($request) {
                $q->where('name', $request->company);
            });
        })
        ->get();
    
    // Initialize array to store payslip data for each user
    $userPayslips = [];
    
    foreach ($users as $user) {
        // Get approved timesheets for this user
        $timeSheets = Timesheet::where('user_email', $user->email)
            ->where('status', 'approved')
            ->orderBy('date', 'asc')
            ->get();
        
        if ($timeSheets->isNotEmpty()) {
            $start_date = $timeSheets->first()->date;
            $end_date = $timeSheets->last()->date;
            
            $current_start_date = $start_date;
            $current_end_date = $this->addTwoWeeks($current_start_date);
            
            $dateRanges = [];
            
            while (true) {
                // Get timesheets for current date range
                $timeSheetsInRange = Timesheet::where('user_email', $user->email)
                    ->where('status', 'approved')
                    ->whereBetween('date', [$current_start_date, $current_end_date])
                    ->get();
                
                if ($timeSheetsInRange->isEmpty()) {
                    break;
                }
                
                // Calculate hours worked
                $hoursWorked = $this->calculateHoursWorked($timeSheetsInRange);
                
                $dateRanges[] = [
                    'start' => $current_start_date,
                    'end' => $current_end_date,
                    'hours' => $hoursWorked
                ];
                
                // Create or update payslip record
                $weekRange = $current_start_date . " - " . $current_end_date;
                $payslip = Payslip::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'week_range' => $weekRange,
                    ],
                    [
                        'reportingTo' => $user->reportingTo,
                        'hrs_worked' => $hoursWorked,
                        'hrlyRate' => $user->hrlyRate,
                    ]
                );
                
                // Move to next week range
                $current_start_date = $this->addOneDay($current_end_date);
                $current_end_date = $this->addTwoWeeks($current_start_date);
            }
            
            // Get the company information for this user
            $company = Company::where('email', $user->reportingTo)->first();
            
            $userPayslips[$user->id] = [
                'user' => $user,
                'dateRanges' => $dateRanges,
                'company' => $company
            ];
        }
    }
    
    return view('admin.payslips', compact('userPayslips', 'companies'));
}

public function generatePayslip($userId, $weekRange)
{
    // Get user data
    $user = RcUsers::findOrFail($userId);
    
    // Get company data from company_tbl using reportingTo email
    $company = Company::where('email', $user->reportingTo)->firstOrFail();
    
    // Get payslip data
    $payslip = Payslip::where('user_id', $userId)
        ->where('week_range', $weekRange)
        ->firstOrFail();

    $company_address = $company->address ?? 'Default Address';

    // Get timesheet details for this period
    list($start_date, $end_date) = explode(" - ", $weekRange);
    $timesheets = Timesheet::where('user_email', $user->email)
        ->where('status', 'approved')
        ->whereBetween('date', [$start_date, $end_date])
        ->orderBy('date', 'asc')
        ->get();

    // Calculate total minutes worked
    $totalMinutes = 0;
    foreach ($timesheets as $timesheet) {
        $timeParts = explode(':', $timesheet->work_time);
        if (count($timeParts) == 3) {
            $hours = (int)$timeParts[0];
            $minutes = (int)$timeParts[1];
            $seconds = (int)$timeParts[2];

            $totalMinutes += ($hours * 60) + $minutes + ($seconds / 60);
        }
    }

    // Convert total minutes to hours and minutes
    $hour_worked = floor($totalMinutes / 60);
    $minutes_worked = $totalMinutes % 60;

    // Convert to decimal hours
    $total_hours_decimal = $hour_worked + ($minutes_worked / 60);
    $hrs_worked = number_format($total_hours_decimal, 2);

    // Calculate earnings
    $hourly_rate = $user->hrlyRate;
    $gross_earning = $hourly_rate * $hrs_worked;
    $annual_leave = 0.073421 * $hrs_worked;

    $currency = $user->currency ?? 'NPR';

    // Update payslip
    $payslip->hrs_worked = $hrs_worked;
    $payslip->gross_earning = $gross_earning;
    $payslip->save();

    // Prepare PDF data
    $data = [
        'company' => $company,
        'user' => $user,
        'payslip' => $payslip,
        'timesheets' => $timesheets,
        'gross_earning' => $gross_earning,
        'company_address' => $company_address,
        'currency' => $currency,
        'hourly_rate' => $hourly_rate,
        'hrs_worked' => $hrs_worked,
        'annual_leave' => $annual_leave,
    ];
    
    // Generate PDF
    $pdf = PDF::loadView('admin.payslips_pdf', $data);
    $pdf->setPaper('a4', 'portrait');
    
    $filename = 'payslip_' . $user->name . '_' . str_replace(' ', '_', $weekRange) . '.pdf';
    
    return $pdf->stream($filename);
}

private function calculateHoursWorked($timeSheets)
{
    $totalMinutes = 0;
    foreach ($timeSheets as $timeSheet) {
        $timeParts = explode(':', $timeSheet->work_time);
        if (count($timeParts) == 3) {
            $hours = (int)$timeParts[0];
            $minutes = (int)$timeParts[1];
            $seconds = (int)$timeParts[2];

            $totalMinutes += ($hours * 60) + $minutes + ($seconds / 60);
        }
    }

    $hour_worked = floor($totalMinutes / 60);
    $minutes_worked = $totalMinutes % 60;
    $total_hours_decimal = $hour_worked + ($minutes_worked / 60);

    return number_format($total_hours_decimal, 2);
}

private function addTwoWeeks($starting_date)
{
    $date = new DateTime($starting_date);
    $date->modify('+15 days');
    return $date->format('Y-m-d');
}

private function addOneDay($starting_date)
{
    $date = new DateTime($starting_date);
    $date->modify('+1 day');
    return $date->format('Y-m-d');
}

}
