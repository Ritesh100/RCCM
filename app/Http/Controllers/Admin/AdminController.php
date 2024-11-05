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
use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Exports\CompanyTimesheetExport;
use Maatwebsite\Excel\Facades\Excel;

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
    
    public function showCompany(Request $request)
    {
        // Initialize query builder for companies
        $companiesQuery = Company::query();
    
        // Handle search functionality
        $searchQuery = $request->input('search');
        if ($searchQuery) {
            // Filter companies by name
            $companiesQuery->where('name', 'LIKE', "%{$searchQuery}%");
        }
    
        // Get all companies with pagination
        $companies = $companiesQuery->paginate(10); // You can change 10 to any number you prefer
    
        return view('admin.company', compact('companies', 'searchQuery')); // Pass companies and searchQuery to view
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

    public function showUsers(Request $request)
    {
        // Initialize query builder for users
        $usersQuery = RcUsers::query();
    
        // Handle search functionality
        $searchQuery = $request->input('search');
        if ($searchQuery) {
            // Filter users by name
            $usersQuery->where('name', 'LIKE', "%{$searchQuery}%");
        }
    
        // Get all users with pagination
        $users = $usersQuery->paginate(10); // You can change 10 to any number you prefer
    
        return view('admin.users', compact('users', 'searchQuery')); // Pass users and searchQuery to view
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
            'currency' => 'nullable|string',
        ]);

        RcUsers::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'reportingTo' => $request->reportingTo,
            'hrlyRate' => $request->hrlyRate,
            'address' => $request->address,
            'contact' => $request->contact,
            'currency' =>$request -> currency,

        ]);

        return redirect()->route('admin.users')->with('success', 'RC created successfully.');
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
            'currency' => 'required|string',

        ]);

        $users->name = $request->name;
        $users->email = $request->email;
        $users->address = $request->address;
        $users->contact = $request->contact;
        $users->reportingTo = $request->reportingTo;
        $users->hrlyRate = $request->hrlyRate;
        $users->currency = $request->currency; 



        if ($request->password) {
            $users->password = Hash::make($request->password);
        }

        $users->save();

        return redirect()->route('admin.users')->with('success', 'RC updated successfully.');
    }

    // Delete a company
    public function deleteUsers($id)
    {
        $users = RcUsers::findOrFail($id);
        $users->delete();

        return redirect()->route('admin.users')->with('success', 'RC deleted successfully.');
    }

    public function showDocument(Request $request)
{
    $user = Auth::user();

    if (!$user) {
        return redirect()->route('login')->with('error', 'User session not found. Please log in again.');
    }

    // Initialize query builder for documents
    $documentsQuery = Document::query();

    // Handle search functionality
    $searchQuery = $request->input('search');
    if ($searchQuery) {
        // Filter documents by name
        $documentsQuery->where('name', 'LIKE', "%{$searchQuery}%");
    }

    // Get all documents with pagination
    $documents = $documentsQuery->paginate(10); // Adjust the number of items per page as desired

    return view('admin.document', compact('documents', 'searchQuery')); // Pass documents and searchQuery to the view
}


    public function deleteDocument($id)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'User session not found. Please log in again.');
        }
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
    public function showInvoice(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'User session not found. Please log in again.');
        }
        $invoices = Invoice::all();
        
      // Retrieve the search query from the request
    $searchQuery = $request->input('search');

    // Filter invoices based on the search query
    $invoices = Invoice::when($searchQuery, function ($query, $searchQuery) {
        return $query->where('invoice_for', 'LIKE', '%' . $searchQuery . '%');
    })->get();

    return view('admin.invoice', compact('invoices', 'searchQuery'));
    }

    public function createInvoice()
    {
        $admin = User::first();
        $users = RcUsers::all();

        // $invoice_number = "rcc_" . random_int(0, 999999);
        $invoice_number = "rcc_" . time() . '_' . random_int(0, 9999);

        return view('admin.createInvoice', compact('admin', 'users', 'invoice_number'));
    }

    public function storeInvoice(Request $request, $rc_partner_id)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'User session not found. Please log in again.');
        }
        // Validate the incoming request data
        $validatedData = $request->validate([
            'week_start' => 'required|date',
            'week_end' => 'required|date',
            'invoice_for' => 'required',
            'email' => 'required|email',
            'invoice_from' => 'required',
            'invoice_address_from' => 'required',
            'contact_email' => 'required|email',
            'invoice_number' => 'required',
            'charges' => 'required|array',
            'charges.*.name' => 'required',
            'charges.*.total' => 'required|numeric',
            'total_charge_rcs' => 'required|numeric',
            'total_transferred_rcs' => 'required|numeric',
            'previous_credits' => 'required|numeric',
            'invoice_images' => 'required|array',
            'invoice_images.*' => 'mimes:jpg,jpeg,png,gif|max:2048'
        ]);



        $chargeNames = [];
        $chargeTotals = [];

 

    foreach ($request->input('charges') as $charge) {
        if (!isset($charge['name']) || !isset($charge['total'])) {
            return redirect()->back()->withErrors(['charges' => 'Invalid charge data provided.']);
        }
        $chargeNames[] = $charge['name'];
        $chargeTotals[] = $charge['total'];
    }

    $encodedChargeNames = json_encode($chargeNames, JSON_THROW_ON_ERROR);
    $encodedChargeTotals = json_encode($chargeTotals, JSON_THROW_ON_ERROR);

    // Store each uploaded file and collect paths
    $paths = [];
    if ($files = $request->file('invoice_images')) {
        foreach ($files as $image) {
            try {
                $path = $image->store('invoices', 'public');
                $paths[] = $path;
            } catch (\Exception $e) {
                return redirect()->back()->withErrors(['invoice_images' => 'File upload failed.']);
            }
        }
    }
    $encodedPath = json_encode($paths, JSON_THROW_ON_ERROR);

    // Create the invoice
    Invoice::create([
        'week_range' => "{$request->week_start} - {$request->week_end}",
        'rc_partner_id' => $rc_partner_id,
        'invoice_for' => $request->invoice_for,
        'email' => $request->email,
        'invoice_from' => $request->invoice_from,
        'invoice_address_from' => $request->invoice_address_from,
        'invoice_number' => $request->invoice_number,
        'total_charge' => $request->total_charge_rcs,
        'total_transferred' => $request->total_transferred_rcs,
        'previous_credits' => $request->previous_credits,
        'charge_name' => $encodedChargeNames,
        'charge_total' => $encodedChargeTotals,
        'image_path' => $encodedPath,
    ]);

    return redirect()->route('admin.invoice')->with('success', 'Invoice created successfully.');
}
public function editInvoice($id)
{
    $admin = User::first();
    $users = RcUsers::all();
    $invoice = Invoice::findOrFail($id); // Retrieve the invoice by ID or throw a 404

    // Decode JSON fields
    $invoice->charge_names = json_decode($invoice->charge_name);
    $invoice->charge_totals = json_decode($invoice->charge_total);
    $invoice->image_paths = json_decode($invoice->image_path);

    return view('admin.editInvoice', compact('admin', 'users', 'invoice'));
}
public function updateInvoice(Request $request, $id)
{
    // Validate the incoming request data
    $validatedData = $request->validate([
        'week_start' => 'required|date',
        'week_end' => 'required|date',
        'invoice_for' => 'required',
        'email' => 'required|email',
        'invoice_from' => 'required',
        'invoice_address_from' => 'required',
        'contact_email' => 'required|email',
        'invoice_number' => 'required',
        'charges' => 'required|array',
        'charges.*.name' => 'required|string',
        'charges.*.total' => 'required|numeric',
        'total_charge_rcs' => 'required|numeric',
        'total_transferred_rcs' => 'required|numeric',
        'previous_credits' => 'required|numeric',
        'invoice_images' => 'array',
        'invoice_images.*' => 'mimes:jpg,jpeg,png,gif|max:2048',
    ]);

    // Find the invoice
    $invoice = Invoice::findOrFail($id);

    // Update the invoice details
    $invoice->week_range = "{$request->week_start} - {$request->week_end}";
    $invoice->invoice_for = $request->invoice_for;
    $invoice->email = $request->email;
    $invoice->invoice_from = $request->invoice_from;
    $invoice->invoice_address_from = $request->invoice_address_from;
    $invoice->invoice_number = $request->invoice_number;
    $invoice->total_charge = $request->total_charge_rcs;
    $invoice->total_transferred = $request->total_transferred_rcs;
    $invoice->previous_credits = $request->previous_credits;

    // Handle charges
    $chargeNames = [];
    $chargeTotals = [];

    foreach ($request->input('charges') as $charge) {
        if (!isset($charge['name']) || !isset($charge['total'])) {
            return redirect()->back()->withErrors(['charges' => 'Invalid charge data provided.']);
        }
        $chargeNames[] = $charge['name'];
        $chargeTotals[] = $charge['total'];
    }

    $encodedChargeNames = json_encode($chargeNames, JSON_THROW_ON_ERROR);
    $encodedChargeTotals = json_encode($chargeTotals, JSON_THROW_ON_ERROR);
    $invoice->charge_name = $encodedChargeNames;
    $invoice->charge_total = $encodedChargeTotals;

    // Handle image uploads
    $paths = $invoice->image_path ? json_decode($invoice->image_path) : [];
    if ($files = $request->file('invoice_images')) {
        foreach ($files as $image) {
            try {
                $path = $image->store('invoices', 'public');
                $paths[] = $path;
            } catch (\Exception $e) {
                return redirect()->back()->withErrors(['invoice_images' => 'File upload failed.']);
            }
        }
    }
    $encodedPath = json_encode($paths, JSON_THROW_ON_ERROR);
    $invoice->image_path = $encodedPath;

    // Save the updated invoice
    $invoice->save();

    return redirect()->route('admin.invoice')->with('success', 'Invoice updated successfully.');
}

public function destroyInvoice($id)
{
    $invoice = Invoice::findOrFail($id);
    $invoice->delete();

    return redirect()->route('admin.invoice')->with('success', 'Invoice deleted successfully.');
}


    
    public function generateInvoicePdf($id)
    {
        $invoices = Invoice::where('id', $id)->get();
        $charge_names = [];
        $charge_totals = [];
        $images = [];

        $admin = Auth::user();

        foreach ($invoices as $invoice) {
            $charge_names[] = json_decode($invoice->charge_name);
            $charge_totals[] = json_decode($invoice->charge_total);
            $images[] = json_decode($invoice->image_path);

            $credit = $invoice->previous_credits + $invoice->total_charge - $invoice->total_transferred;
            $issued_on = $invoice->created_at; // This retrieves the created_at date
            $address = $invoice->invoice_address_from;

        }
        
        $pdf = Pdf::loadView('admin.invoicePdf', [
            'invoices' => $invoices,
            'charge_names' => $charge_names,
            'charge_totals' => $charge_totals,
            'credit' => $credit,
            'issued_on' =>$issued_on ,
            'address' => $address,
            'admin_abn' => $admin->abn, // Assuming 'abn' is a field in the User model
            'admin_address' => $admin->address // Assuming 'address' is a field in the User model
        ]);        
        return $pdf->stream();
    }



    public function showPayslips(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'User session not found. Please log in again.');
        }
        // Get all companies
        $companies = Company::all();

         // Get users with optional search filter for name or email
    $users = RcUsers::when($request->has('search'), function ($query) use ($request) {
        return $query->where(function ($q) use ($request) {
            $q->where('name', 'LIKE', '%' . $request->search . '%')
              ->orWhere('email', 'LIKE', '%' . $request->search . '%');
        });
    })
    ->when($request->has('company'), function ($query) use ($request) {
        return $query->whereHas('company', function ($q) use ($request) {
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

        return view('admin.payslips', compact('userPayslips', 'companies'))->with('searchQuery', $request->search);
    }

    public function generatePayslip($userId, $weekRange)
    {
        
        $admin = Auth::user();

        if (!$admin) {
            return redirect()->route('login')->with('error', 'User session not found. Please log in again.');
        }// Get user data
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
public function showAllTimesheets(Request $request)
{
    $admin = Auth::user();

    if (!$admin) {
        return redirect()->route('login')->with('error', 'User session not found. Please log in again.');
    }

    $timesheetsQuery = Timesheet::query();
    $companiesQuery = Company::query();

    $uniqueUsernames = RcUsers::pluck('name')->unique();
    $uniqueDays = Timesheet::pluck('day')->unique();
    $uniqueCostCenters = Timesheet::pluck('cost_center')->unique();
    $uniqueStatuses = Timesheet::pluck('status')->unique();
    $uniqueDates = Timesheet::pluck('date')->unique(); // Assuming your date field is named 'date'


    $searchQuery = $request->input('search');
    
    // Apply filters based on search fields
    if ($searchQuery) {
        $companiesQuery->where('name', 'LIKE', "%{$searchQuery}%");
        $companyEmails = $companiesQuery->pluck('email')->toArray();
        $userEmails = RcUsers::whereIn('reportingTo', $companyEmails)
            ->pluck('email')
            ->toArray();
        $userNames = RcUsers::where('name', 'LIKE', "%{$searchQuery}%")
            ->pluck('email')
            ->toArray();
        $userEmails = array_merge($userEmails, $userNames);
        $timesheetsQuery->whereIn('user_email', $userEmails);
    }

    // Filter by username if provided
    if ($request->filled('username')) {
        $username = $request->input('username');
        // Assuming 'user_email' links timesheets to RcUsers, you may need to adjust this field
        $userEmails = RcUsers::where('name', $username)->pluck('email');
        $timesheetsQuery->whereIn('user_email', $userEmails);
    }

    // Additional filters for 'day', 'cost_center', 'status'
    if ($request->filled('day')) {
        $timesheetsQuery->where('day', $request->input('day'));
    }
    if ($request->filled('cost_center')) {
        $timesheetsQuery->where('cost_center', $request->input('cost_center'));
    }
    if ($request->filled('status')) {
        $timesheetsQuery->where('status', $request->input('status'));
    }
    if ($request->filled('date')) {
        $timesheetsQuery->where('date', $request->input('date'));
    }

    // Fetch filtered timesheets with pagination
    $timesheets = $timesheetsQuery->paginate(10);

    // Enhance timesheet data with user and company info
    $timesheets->each(function ($timesheet) {
        $user = RcUsers::where('email', $timesheet->user_email)->first();
        if ($user) {
            $timesheet->user_name = $user->name;
            $company = Company::where('email', $user->reportingTo)->first();
            if ($company) {
                $timesheet->company_name = $company->name;
                $timesheet->company_email = $company->email;
            }
        }
    });
    return view('admin.timesheets', compact(
        'timesheets',
        'uniqueUsernames',
        'uniqueDays',
        'searchQuery',
        'uniqueCostCenters',
        'uniqueDates',
        'uniqueStatuses'
    ));
}


// public function showCompanyTimesheets(Request $request, $companyId)
// {
//     // Find the company
//     $company = Company::findOrFail($companyId);
    
//     // Get all users reporting to this company
//     $company_users = RcUsers::where('reportingTo', $company->email);
    
//     // Handle search within company
//     $searchQuery = $request->input('search');
//     if ($searchQuery) {
//         $company_users = $company_users->where('name', 'LIKE', "%{$searchQuery}%");
//     }
    
//     // Get filtered users
//     $company_users = $company_users->get();
//     $userEmails = $company_users->pluck('email')->toArray();
    
//     // Get timesheets for these users
//     $timesheets = Timesheet::whereIn('user_email', $userEmails)->paginate(10);
    
//     // Add user names to timesheet data
//     $timesheets->each(function ($timesheet) use ($company_users) {
//         $timesheet->name = $company_users->firstWhere('email', $timesheet->user_email)->name ?? 'N/A';
//     });

//     return view('admin.company-timesheets', compact('timesheets', 'company', 'searchQuery'));
// }

public function updateStatus(Request $request, $id)
{
    $admin = Auth::user();

    if (!$admin) {
        return redirect()->route('login')->with('error', 'User session not found. Please log in again.');
    }
    $timesheet = Timesheet::findOrFail($id);
    
    $newStatus = $request->input('status');
    
    switch($newStatus) {
        case 'approved':
            if ($timesheet->status !== 'approved') {
                $timesheet->status = 'approved';
                $timesheet->save();
                return redirect()->back()->with('success', 'Timesheet approved successfully!');
            }
            break;
        
        case 'pending':
            $timesheet->status = 'pending';
            $timesheet->save();
            return redirect()->back()->with('success', 'Timesheet set to pending.');
            break;
        
        case 'deleted':
            $timesheet->delete();
            return redirect()->back()->with('success', 'Timesheet deleted successfully!');
            break;
        
        default:
            return redirect()->back()->with('error', 'Invalid status or timesheet already in the selected status.');
    }
}

public function updateTimesheet(Request $request, $id)
{
    $admin = Auth::user();

    if (!$admin) {
        return redirect()->route('login')->with('error', 'User session not found. Please log in again.');
    }
    $timesheet = Timesheet::findOrFail($id);

    // Update the timesheet with the new data
    $timesheet->day = $request->input('day');
    $timesheet->user_email = $request->input('user_email');
    $timesheet->cost_center = $request->input('cost_center');
    $timesheet->currency = $request->input('currency');
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
public function exportByCompany($companyId, $status = null)
{
    return Excel::download(new CompanyTimesheetExport($companyId, $status), 
        'company_timesheets_' . $companyId . ($status ? "_{$status}" : '') . '.xlsx'
    );
}

}
