<?php

namespace App\Http\Controllers\Admin;

use DateTime;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Company;
use App\Models\Invoice;
use App\Models\Payslip;
use App\Models\RcUsers;
use App\Models\Document;
use App\Models\Timesheet;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CompanyTimesheetExport;
use App\Models\Leave;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function showProfile()
    {
        $user = Auth::user();
        return view('admin.profile', compact('user'));
    }

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
        $companiesQuery = Company::query();

        $searchQuery = $request->input('search');
        if ($searchQuery) {
            $companiesQuery->where('name', 'LIKE', "%{$searchQuery}%");
        }

        $companies = $companiesQuery->paginate(10); // You can change 10 to any number you prefer

        return view('admin.company', compact('companies', 'searchQuery')); // Pass companies and searchQuery to view
    }


    public function createCompany()
    {
        return view('admin.create_company'); // Create this view
    }

    public function storeCompany(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:company_tbl,email',
            'password' => 'required|string|min:4',
            'address' => 'string|nullable',
            'contact' => 'string|nullable',
        ]);

        Company::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'address' => $request->address,
            'contact' => $request->contact,
        ]);

        return redirect()->route('admin.company');
    }

    public function editCompany($id)
    {
        $company = Company::findOrFail($id);
        return view('admin.edit_company', compact('company')); // Create this view
    }

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

    public function deleteCompany($id)
    {
        $company = Company::findOrFail($id);
        $company->delete();

        return redirect()->route('admin.company')->with('success', 'Company deleted successfully.');
    }

    public function showUsers(Request $request)
    {
        $usersQuery = RcUsers::query();

        $searchQuery = $request->input('search');
        if ($searchQuery) {
            $usersQuery->where('name', 'LIKE', "%{$searchQuery}%");
        }

        $users = $usersQuery->paginate(10); // You can change 10 to any number you prefer

        return view('admin.users', compact('users', 'searchQuery')); // Pass users and searchQuery to view
    }


    public function createUsers()
    {
        $companies = Company::select('name', 'email')->get();


        return view('admin.create_users', compact('companies')); // Create this view
    }

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

    public function editUsers($id)
    {
        $users = RcUsers::findOrFail($id);
        return view('admin.edit_users', compact('users')); // Create this view
    }

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
        $documents = $documentsQuery->paginate(10);

        // Retrieve all users for the dropdown
        $users = RcUsers::all();

        return view('admin.document', compact('documents', 'searchQuery', 'users'));
    }


    public function deleteDocument($id)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'User session not found. Please log in again.');
        }
        $document = Document::find($id);

        $document = Document::where('id', $id)
            ->first();


        if ($document) {
            if (Storage::exists($document->path)) {
                Storage::delete($document->path);
            }

            $document->delete();

            return redirect()->back()->with('success', 'Document deleted successfully.');
        }

        return redirect()->back()->with('error', 'Document not found or unauthorized.');
    }
    public function showInvoice(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'User session not found. Please log in again.');
        }
        $invoices = Invoice::all();

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
        $companies = Company::all();
        // $invoice_number = "rcc_" . random_int(0, 999999);
        $invoice_number = "rcc_" . time() . '_' . random_int(0, 9999);

        return view('admin.createInvoice', compact('admin', 'companies', 'invoice_number'));
    }

    public function storeInvoice(Request $request, $rc_partner_id)
    {
        $companies = Company::all();
        if (!$companies) {
            return redirect()->route('login')->with('error', ' session not found. Please log in again.');
        }

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
            'currency' => 'required|string',
            'charges.*.name' => 'required|string',
            'charges.*.total' => 'required|numeric',
            'total_charge_rcs' => 'required|numeric',
            'total_transferred_rcs' => 'required|numeric',
            'invoice_images' => 'required|array',
            'invoice_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'nullable|in:Pending,Paid',
        ]);

        $chargeNames = array_column($request->input('charges'), 'name');
        $chargeTotals = array_column($request->input('charges'), 'total');

        $encodedChargeNames = json_encode($chargeNames, JSON_THROW_ON_ERROR);
        $encodedChargeTotals = json_encode($chargeTotals, JSON_THROW_ON_ERROR);

        $total_credit = $request->previous_credits + ($request->total_transferred_rcs - $request->total_charge_rcs);

        $latestInvoice = Invoice::where('invoice_for', $request->invoice_for)
                                ->orderBy('created_at', 'desc')
                                ->first();

        $previous_credits = $latestInvoice ? $latestInvoice->total_credit : 0;

        $paths = [];
        if ($files = $request->file('invoice_images')) {
            Log::info('Files received:', $files);
            foreach ($files as $image) {
                try {
                    $path = $image->store('invoices', 'public');
                    $paths[] = $path; // Collect each path
                } catch (\Exception $e) {
                    Log::error('File upload error: ' . $e->getMessage());
                    return redirect()->back()->withErrors(['invoice_images' => 'File upload failed: ' . $e->getMessage()]);
                }
            }
        }

        $encodedPath = json_encode($paths, JSON_THROW_ON_ERROR);

        Invoice::create([
            'week_range' => "{$request->week_start} - {$request->week_end}",
            'rc_partner_id' => $rc_partner_id,
            'invoice_for' => $request->invoice_for,
            'email' => $request->email,
            'invoice_from' => $request->invoice_from,
            'invoice_address_from' => $request->invoice_address_from,
            'currency' => $request->currency,
            'invoice_number' => $request->invoice_number,
            'total_charge' => $request->total_charge_rcs,
            'total_transferred' => $request->total_transferred_rcs,
            'previous_credits' => $previous_credits,
            'charge_name' => $encodedChargeNames,
            'charge_total' => $encodedChargeTotals,
            'image_path' => $encodedPath,
            'total_credit' => $total_credit,
        ]);

        return redirect()->route('admin.invoice')->with('success', 'Invoice created successfully.');
    }


    public function getPreviousCredits($invoice_for)
    {
        $latestInvoice = Invoice::where('invoice_for', $invoice_for)
                                ->orderBy('updated_at', 'desc')
                                ->first();

        return response()->json([
            'previous_credits' => $latestInvoice ? $latestInvoice->total_credit : 0
        ]);
    }


public function editInvoice($id)
{
    $admin = User::first();
    $companies = Company::all();
    $invoice = Invoice::findOrFail($id);

    // Decode JSON fields
    $invoice->charge_names = json_decode($invoice->charge_name);
    $invoice->charge_totals = json_decode($invoice->charge_total);
    $invoice->total_credit = json_decode($invoice->total_credit);

    $invoice->image_paths = json_decode($invoice->image_path);

    return view('admin.editInvoice', compact('admin', 'companies', 'invoice'));
}
public function updateInvoice(Request $request, $id)
{
   $validatedData = $request->validate([
    'week_start' => 'required|date',
    'week_end' => 'required|date',
    'invoice_for' => 'required',
    'email' => 'required|email',
    'invoice_from' => 'required',
    'invoice_address_from' => 'required',
    'contact_email' => 'required|email',
    'currency' => 'required|string',
    'invoice_number' => 'required',
    'charges' => 'required|array',
    'charges.*.name' => 'required|string',
    'charges.*.total' => 'required|numeric',
    'total_charge_rcs' => 'required|numeric',
    'total_transferred_rcs' => 'required|numeric',
    'previous_credits' => 'required|numeric',
    'invoice_images' => 'array',
    'invoice_images.*' => 'mimes:jpg,jpeg,png,gif|max:2048',
    'status' => 'required|in:Pending,Paid',
]);

    $invoice = Invoice::findOrFail($id);

    $invoice->week_range = "{$request->week_start} - {$request->week_end}";
    $invoice->invoice_for = $request->invoice_for;
    $invoice->email = $request->email;
    $invoice->invoice_from = $request->invoice_from;
    $invoice->invoice_address_from = $request->invoice_address_from;
    $invoice->invoice_number = $request->invoice_number;
    $invoice->total_charge = $request->total_charge_rcs;
    $invoice->total_transferred = $request->total_transferred_rcs;
    $invoice->previous_credits = $request->previous_credits;
    $invoice->currency = $request->currency;
    $invoice->status = $request->status;
    $invoice->total_credit = $request->previous_credits + ($request->total_transferred_rcs - $request->total_charge_rcs);
    $chargeNames = [];
    $chargeTotals = [];

    foreach ($request->input('charges') as $charge) {
        $chargeNames[] = $charge['name'];
        $chargeTotals[] = $charge['total'];
    }
    $invoice->charge_name = json_encode($chargeNames, JSON_THROW_ON_ERROR);
    $invoice->charge_total = json_encode($chargeTotals, JSON_THROW_ON_ERROR);

    $paths = $invoice->image_path ? json_decode($invoice->image_path, true) : [];

    if ($request->has('removed_images')) {
        $removedImages = $request->input('removed_images');
        foreach ($removedImages as $removedImage) {
            if (($key = array_search($removedImage, $paths)) !== false) {
                unset($paths[$key]);
                Storage::disk('public')->delete($removedImage);
            }
        }
        $paths = array_values($paths); // Reindex array
    }

    if ($files = $request->file('invoice_images')) {
        foreach ($files as $image) {
            $path = $image->store('invoices', 'public');
            $paths[] = $path;
        }
    }
    $invoice->image_path = json_encode($paths, JSON_THROW_ON_ERROR);

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
        $previousCredit = json_decode($invoice->previous_credits);
        $accumulatedCredit = $invoice->total_transferred -  $invoice->total_charge ;
        $credit = $invoice->previous_credits   + ( $invoice->total_transferred -  $invoice->total_charge);
        $issued_on = $invoice->created_at;
        $address = $invoice->invoice_address_from;

        // Decode the JSON-encoded image paths
        $imagePaths = json_decode($invoice->image_path);
        if ($imagePaths) {
            foreach ($imagePaths as $path) {
                // Get the full storage path
                $fullPath = storage_path('app/public/' . $path);

                // Check if file exists
                if (file_exists($fullPath)) {
                    // Convert image to base64 for PDF embedding
                    $imageData = base64_encode(file_get_contents($fullPath));
                    $images[] = [
                        'path' => $fullPath,
                        'base64' => $imageData,
                        'mime' => mime_content_type($fullPath)
                    ];
                }
            }
        }
    }

    $pdf = Pdf::loadView('admin.invoicePdf', [
        'invoices' => $invoices,
        'charge_names' => $charge_names,
        'charge_totals' => $charge_totals,
        'previousCredit' => $previousCredit,
        'accumulatedCredit' => $accumulatedCredit,
        'credit' => $credit,
        'issued_on' => $issued_on,
        'address' => $address,
        'admin_abn' => $admin->abn,
        'admin_name' =>$admin->userName,
        'admin_address' => $admin->address,
        'images' => $images, // Pass the images to the view
        'admin' => $admin
    ]);

    return $pdf->stream();
}
public function showPayslips(Request $request)
{
    $user = Auth::user();

    if (!$user) {
        return redirect()->route('login')->with('error', 'User session not found. Please log in again.');
    }

    // Handle deletion request
    if ($request->has('action') && $request->input('action') == 'delete') {
        try {
            $deleteUserId = $request->input('userId');
            $deleteWeekRange = $request->input('weekRange');

            $payslip = Payslip::where('user_id', $deleteUserId)
                ->where('week_range', $deleteWeekRange)
                ->first();

            if ($payslip) {
                $payslip->status = 'deleted';
                $payslip->deleted_at = now();
                $payslip->save();

                return redirect()->route('admin.payslips')
                    ->with('success', 'Payslip marked as deleted successfully.');
            }

            return redirect()->route('admin.payslips')
                ->with('error', 'Payslip not found.');
        } catch (\Exception $e) {
            Log::error('Payslip deletion error: ' . $e->getMessage());
            return redirect()->route('admin.payslips')
                ->with('error', 'Failed to delete payslip. Please try again.');
        }
    }

    // Fetch relevant data
    $payslips = Payslip::where('status', 'active')->get();
    $companies = Company::all();
// Get unique usernames and emails for dropdowns
$uniqueUsernames = RcUsers::select('name')->distinct()->pluck('name');
$uniqueUseremails = RcUsers::select('email')->distinct()->pluck('email');


    // Filter users based on the request
    $users = RcUsers::when($request->filled('username'), function ($query) use ($request) {
            $query->where('name', $request->username);
        })
        ->when($request->filled('useremail'), function ($query) use ($request) {
            $query->where('email', $request->useremail);
        })
        // ->when($request->filled('search'), function ($query) use ($request) {
        //     $query->where(function ($q) use ($request) {
        //         $q->where('name', 'LIKE', '%' . $request->search . '%')
        //           ->orWhere('email', 'LIKE', '%' . $request->search . '%');
        //     });
        // })
        ->get();

    // Prepare the payslip data for each user
    $userPayslips = [];
    foreach ($users as $user) {
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
            while ($current_start_date <= $end_date) {
                $timeSheetsInRange = Timesheet::where('user_email', $user->email)
                    ->whereBetween('date', [$current_start_date, $current_end_date])
                    ->where('status', 'approved')
                    ->get();

                if ($timeSheetsInRange->isEmpty()) {
                    $current_start_date = $this->addOneDay($current_end_date);
                    $current_end_date = $this->addTwoWeeks($current_start_date);
                    continue;
                }
                $leave = Leave::where('user_id', $user->id)->first();


                $pendingTimeSheetsInRange = Timesheet::where('user_email', $user->email)
                    ->whereBetween('date', [$current_start_date, $current_end_date])
                    ->where('status', 'pending')
                    ->exists();

                if ($pendingTimeSheetsInRange) {
                    $dateRanges[] = [
                        'start' => $current_start_date,
                        'end' => $current_end_date,
                        'status' => 'pending',
                        'hours' => null
                    ];
                } else {
                    $hoursWorked = $this->calculateHoursWorked($timeSheetsInRange, $leave);


                    $existingPayslip = Payslip::where('user_id', $user->id)
                        ->where('week_range', $current_start_date . " - " . $current_end_date)
                        ->first();

                    if (!$existingPayslip) {
                        Payslip::create([
                            'user_id' => $user->id,
                            'reportingTo' => $user->reportingTo,
                            'week_range' => $current_start_date . " - " . $current_end_date,
                            'hrs_worked' => $hoursWorked,
                            'hrlyRate' => $user->hrlyRate,
                            'disable' => true
                        ]);
                    }

                    $dateRanges[] = [
                        'start' => $current_start_date,
                        'end' => $current_end_date,
                        'status' => 'approved',
                        'hours' => $hoursWorked
                    ];
                }

                $current_start_date = $this->addOneDay($current_end_date);
                $current_end_date = $this->addTwoWeeks($current_start_date);
            }

            $company = Company::where('email', $user->reportingTo)->first();

            $userPayslips[$user->id] = [
                'user' => $user,
                'dateRanges' => $dateRanges,
                'company' => $company
            ];
        }
    }

    return view('admin.payslips', compact('userPayslips', 'companies', 'uniqueUsernames', 'uniqueUseremails', 'payslips'))
        ->with('searchQuery', $request->search);
}


public function togglePayslipStatus(Request $request)
{
    $payslip = Payslip::where('user_id', $request->userId)
                      ->where('week_range', $request->weekRange)
                      ->first();

    if ($payslip) {
        // Toggle the disable field
        $payslip->disable = !$payslip->disable;  // This will toggle between 0 and 1
        $payslip->save();
    }

    return redirect()->back();
}

public function deletePayslip(Request $request)
{
    try {
        $userId = $request->input('userId');
        $weekRange = $request->input('weekRange');

        $payslip = Payslip::where('user_id', $userId)
            ->where('week_range', $weekRange)
            ->first();

        if ($payslip) {
            // Soft delete: change status to 'deleted' and set deletion timestamp
            $payslip->status = 'deleted';
            $payslip->deleted_at = now();
            $payslip->save();

            return redirect()->route('admin.payslips')
                ->with('success', 'Payslip deleted successfully.');
        }

        return redirect()->route('admin.payslips')
            ->with('error', 'Payslip not found.');
    } catch (\Exception $e) {
        Log::error('Payslip delete error: ' . $e->getMessage());
        return redirect()->route('admin.payslips')
            ->with('error', 'Failed to delete payslip.');
    }
}

public function restorePayslip(Request $request)
{
    try {
        $userId = $request->input('userId');
        $weekRange = $request->input('weekRange');

        $payslip = Payslip::where('user_id', $userId)
            ->where('week_range', $weekRange)
            ->first();

        if ($payslip) {
            // Restore: change status back to 'active' and clear deletion timestamp
            $payslip->status = 'active';
            $payslip->deleted_at = null;
            $payslip->save();

            return redirect()->route('admin.payslips')
                ->with('success', 'Payslip restored successfully.');
        }

        return redirect()->route('admin.payslips')
            ->with('error', 'Payslip not found.');
    } catch (\Exception $e) {
        Log::error('Payslip restore error: ' . $e->getMessage());
        return redirect()->route('admin.payslips')
            ->with('error', 'Failed to restore payslip.');
    }
}
public function showLeave(Request $request)
{
    $admin = Auth::user();

    if (!$admin) {
        return redirect()->route('login')->with('error', 'User session not found. Please log in again.');
    }

    // Retrieve all users and companies
    $users = RcUsers::all();
    $companies = Company::all();

    // Retrieve search parameters
    $searchCompany = $request->searchCompany;
    $searchUsername = $request->searchUsername;

    // Initialize groupedLeaves as empty
    $groupedLeaves = collect();

    // Only process leaves if there are search filters
    if ($searchCompany || $searchUsername) {
        // Filter companies based on the selected searchCompany
        $filteredCompanies = $companies;
        if ($searchCompany) {
            $filteredCompanies = $companies->filter(function ($company) use ($searchCompany) {
                return $company->id == $searchCompany;
            });
        }

        // Start with a base query for leaves
        $leavesQuery = Leave::with('rcUser');

        // Filter leaves by user if searchUsername is provided
        if ($searchUsername) {
            $leavesQuery->whereHas('rcUser', function ($query) use ($searchUsername) {
                $query->where('id', $searchUsername);
            });
        }

        // Retrieve all leaves
        $leaves = $leavesQuery->get();

        // Group leaves by company
        foreach ($filteredCompanies as $company) {
            // Find all users reporting to this company
            $companyUsers = $users->where('reportingTo', $company->email);

            // Get leaves for users under this company
            $companyLeaves = $leaves->filter(function ($leave) use ($companyUsers) {
                return $companyUsers->contains('id', $leave->rcUser->id);
            });

            // Only add company to results if it has leaves
            if ($companyLeaves->isNotEmpty()) {
                $groupedLeaves->put($company->id, [
                    'company' => $company,
                    'leaves' => $companyLeaves,
                ]);
            }
        }
    }

    // Pass data to the view
    return view('admin.leave', compact('groupedLeaves', 'users', 'companies'));
}



public function editPayslip($userId, $weekRange){

    $admin = Auth::user();

    if (!$admin) {
        return redirect()->route('login')->with('error', 'User session not found. Please log in again.');
    }

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



    return view('admin.editPayslip', [
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

    ]);
}
public function updatePayslip(Request $request) {
    $admin = Auth::user();

    if (!$admin) {
        return redirect()->route('login')->with('error', 'User session not found. Please log in again.');
    }

    $id = $request->input('id');

    $timesheet = Timesheet::where('id', $id)
        ->where('user_email', $request->input('user_email'))
        ->firstOrFail();

    // Update other fields
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

    // Validate work_time
    $workTime = $request->input('work_time');
    $timesheet->work_time = $workTime;

    $timesheet->save();

    return redirect()->back()->with('success', 'Timesheet updated successfully!');
}
// public function deletePayslip(Request $request)
// {
//     try {
//         // Validate the request
//         $request->validate([
//             'userId' => 'required|exists:rc_users,id',
//             'weekRange' => 'required|string'
//         ]);

//         // Find the payslip
//         $payslip = Payslip::where('user_id', $request->userId)
//             ->where('week_range', $request->weekRange)
//             ->first();

//         if ($payslip) {
//             // Soft delete the payslip
//             $payslip->status = 'deleted';
//             $payslip->save();

//             return redirect()->route('admin.payslips')
//                 ->with('success', 'Payslip marked as deleted successfully.');
//         }

//         return redirect()->route('admin.payslips')
//             ->with('error', 'Payslip not found.');

//     } catch (\Exception $e) {
//         // Log the error

//         return redirect()->route('admin.payslips')
//             ->with('error', 'Failed to delete payslip. Please try again.');
//     }
// }


public function addPayslip(Request $request)
    {
        $admin = Auth::user();

        if (!$admin) {
            return redirect()->route('login')->with('error', 'User session not found. Please log in again.');
        }


        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'day' => 'required|string',
            'cost_center' => 'required|string',
            'date' => 'required|date',
            'start_time' => 'required',
            'close_time' => 'required',
            'work_time' => 'required|string', // You might need more specific validation based on the format
            'status' => 'required|string|in:pending,approved',
            'user_email' => 'required|email',
            'reportingTo' => 'required|string',
            'timezone' => 'required|string',
            'currency' => 'required', // Add all available currencies
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Create a new timesheet entry
        $timesheet = new Timesheet();
        $timesheet->day = $request->day;
        $timesheet->cost_center = $request->cost_center;
        $timesheet->date = $request->date;
        $timesheet->start_time = $request->start_time;
        $timesheet->close_time = $request->close_time;
        $timesheet->break_start = $request->break_start ?? null; // Optional field
        $timesheet->break_end = $request->break_end ?? null; // Optional field
        $timesheet->work_time = $request->work_time;
        $timesheet->status = $request->status;
        $timesheet->user_email = $request->user_email;
        $timesheet->reportingTo = $request->reportingTo;
        $timesheet->timezone = $request->timezone;
        $timesheet->currency = $request->currency;

        // Save the timesheet to the database
        $timesheet->save();

        // Return a response
        return response()->json([
            'message' => 'Timesheet added successfully!',
            'data' => $timesheet,
        ], 200);


}

public function toggleDisable($id)
{
    $payslip = Payslip::findOrFail($id);

    // Toggle the 'disable' status
    $payslip->disable = !$payslip->disable;
    $payslip->save();

    return response()->json(['message' => 'Payslip status updated successfully.'], 200);
}

public function generatePayslip($userId, $weekRange)
{
    $admin = Auth::user();
    if (!$admin) {
        return redirect()->route('login')->with('error', 'User session not found. Please log in again.');
    }

    $user = RcUsers::findOrFail($userId);
    $company = Company::where('email', $user->reportingTo)->firstOrFail();
    $payslip = Payslip::where('user_id', $userId)
        ->where('week_range', $weekRange)
        ->firstOrFail();

    $company_address = $company->address ?? 'Default Address';

    list($start_date, $end_date) = explode(" - ", $weekRange);
    $timesheets = Timesheet::where('user_email', $user->email)
        ->where('status', 'approved')
        ->whereBetween('date', [$start_date, $end_date])
        ->orderBy('date', 'asc')
        ->get();

    $totalMinutes = 0;
    foreach ($timesheets as $timesheet) {
        if ($timesheet->cost_center === 'unpaid_leave') {
            continue; // Skip unpaid leave
        }

        $timeParts = explode(':', $timesheet->work_time);
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
    $hrs_worked = number_format($total_hours_decimal, 2);

    $hourly_rate = $user->hrlyRate;
    $gross_earning = $hourly_rate * $hrs_worked;
    $annual_leave = 0.073421 * $hrs_worked;
    $currency = $user->currency ?? 'NPR';

    $payslip->hrs_worked = $hrs_worked;
    $payslip->gross_earning = $gross_earning;
    $payslip->save();

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
        'admin' => $admin
    ];

    $pdf = PDF::loadView('admin.payslips_pdf', $data);
    $pdf->setPaper('a4', 'portrait');

    $filename = 'payslip_' . $user->name . '_' . str_replace(' ', '_', $weekRange) . '.pdf';
    return $pdf->stream($filename);
}
public function getTimesheetsByUserId($userId)
{
    // Fetch user data
    $user = RcUsers::findOrFail($userId);

    // Get all approved timesheets for the user (you can modify the status filter as needed)
    $timesheets = Timesheet::where('user_email', $user->email)
        ->where('status', 'approved') // You can adjust this if you need other statuses
        ->orderBy('date', 'asc') // Sorting by date, adjust as needed
        ->get();

    // You may want to pass these timesheets to a view or return as JSON if you're working with an API
    return view('admin.timesheetDetails', [
        'timesheets' => $timesheets,
        'user' => $user,
    ]);
}

// Helper method to convert decimal hours to HH:MM:SS format
protected function convertHoursToWorkTime($decimalHours)
{
    $hours = floor($decimalHours);
    $minutes = round(($decimalHours - $hours) * 60);

    return sprintf('%02d:%02d:00', $hours, $minutes);
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

    // Create separate queries for pending and approved timesheets
    $pendingQuery = Timesheet::where('status', 'pending');
    $approvedQuery = Timesheet::where('status', 'approved');

    // Get unique values for filters
    $uniqueUsernames = RcUsers::pluck('name')->unique();
    $uniqueDays = Timesheet::pluck('day')->unique();
    $uniqueCostCenters = Timesheet::pluck('cost_center')->unique();
    $uniqueStatuses = Timesheet::pluck('status')->unique();
    $uniqueDates = Timesheet::pluck('date')->unique();
    $uniqueCompanies = Company::pluck('name', 'email')->unique(); // Add this line

    // Apply filters
    if ($request->filled('company_name')) {
        $companyEmail = $request->input('company_name'); // This will be the email from the dropdown value
        $userEmails = RcUsers::where('reportingTo', $companyEmail)->pluck('email');

        $pendingQuery->whereIn('user_email', $userEmails);
        $approvedQuery->whereIn('user_email', $userEmails);
    }


    $searchQuery = $request->input('search');

    // Apply common filters to both queries
    if ($searchQuery) {
        $companiesQuery = Company::where('name', 'LIKE', "%{$searchQuery}%");
        $companyEmails = $companiesQuery->pluck('email')->toArray();
        $userEmails = RcUsers::whereIn('reportingTo', $companyEmails)
            ->pluck('email')
            ->toArray();
        $userNames = RcUsers::where('name', 'LIKE', "%{$searchQuery}%")
            ->pluck('email')
            ->toArray();
        $userEmails = array_merge($userEmails, $userNames);

        $pendingQuery->whereIn('user_email', $userEmails);
        $approvedQuery->whereIn('user_email', $userEmails);
    }

    // Apply other filters to both queries
    foreach (['username', 'day', 'cost_center', 'date'] as $filter) {
        if ($request->filled($filter)) {
            if ($filter === 'username') {
                $userEmails = RcUsers::where('name', $request->input($filter))->pluck('email');
                $pendingQuery->whereIn('user_email', $userEmails);
                $approvedQuery->whereIn('user_email', $userEmails);
            } else {
                $pendingQuery->where($filter, $request->input($filter));
                $approvedQuery->where($filter, $request->input($filter));
            }
        }
    }

    // Paginate both queries separately
    $pendingTimesheets = $pendingQuery->paginate(10, ['*'], 'pending_page');
    $approvedTimesheets = $approvedQuery->paginate(10, ['*'], 'approved_page');

    // Enhance timesheet data with user and company info
    $enhanceTimesheets = function($timesheets) {
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
    };

    $enhanceTimesheets($pendingTimesheets);
    $enhanceTimesheets($approvedTimesheets);

    return view('admin.timesheets', compact(
        'pendingTimesheets',
        'approvedTimesheets',
        'uniqueUsernames',
        'uniqueDays',
        'searchQuery',
        'uniqueCostCenters',
        'uniqueDates',
        'uniqueStatuses',
        'uniqueCompanies' // Add this

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

public function bulkUpdate(Request $request)
{
    $timesheetIds = json_decode($request->timesheet_ids);
    $status = $request->status;

    if ($status === 'delete') {
        // Handle deletion
        Timesheet::whereIn('id', $timesheetIds)->delete();
        $message = 'Selected timesheets have been deleted.';
    } else {
        // Handle status update
        Timesheet::whereIn('id', $timesheetIds)->update(['status' => $status]);
        $message = 'Selected timesheets have been updated.';
    }

    return redirect()->back()->with('success', $message);
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
private function calculateHoursWorked($timeSheetsInRange, $leave) {
    // Calculate total minutes worked
    $totalMinutes = 0;
    $leaveTypeTotals = [
        'sick_leave' => 0,
        'annual_leave' => 0,
        'public_holiday' => 0,
        'unpaid_leave' => 0
    ];

    foreach ($timeSheetsInRange as $timeSheet) {
        if ($timeSheet->cost_center === 'unpaid_leave') {
            continue; // Skip unpaid leave
        }

        // Track minutes for each leave type
        if (in_array($timeSheet->cost_center, array_keys($leaveTypeTotals))) {
            $timeParts = explode(':', $timeSheet->work_time);
            if (count($timeParts) == 3) {
                $hours = (int)$timeParts[0];
                $minutes = (int)$timeParts[1];
                $seconds = (int)$timeParts[2];
                $leaveTypeTotals[$timeSheet->cost_center] += ($hours * 60) + $minutes + ($seconds / 60);
            }
        } else {
            // Regular work time
            $timeParts = explode(':', $timeSheet->work_time);
            if (count($timeParts) == 3) {
                $hours = (int)$timeParts[0];
                $minutes = (int)$timeParts[1];
                $seconds = (int)$timeParts[2];
                $totalMinutes += ($hours * 60) + $minutes + ($seconds / 60);
            }
        }
    }

    // Add leave type minutes to total minutes
    $totalMinutes += $leaveTypeTotals['sick_leave'] +
                     $leaveTypeTotals['annual_leave'] +
                     $leaveTypeTotals['public_holiday'];

    // Convert total minutes back to hours and minutes
    $hoursWorked = floor($totalMinutes / 60);
    $minutesWorked = $totalMinutes % 60;

    // Convert total time to decimal hours
    $total_hours_decimal = $hoursWorked + ($minutesWorked / 60);

    // Format the result to 2 decimal places
    return number_format($total_hours_decimal, 2);
}
private function generateWeekRanges($allTimesheets)
{
    $weekRanges = [];

    foreach ($allTimesheets as $timesheets) {
        $start_date = $timesheets->first()->date;
        $end_date = $timesheets->last()->date;

        $current_start_date = $start_date;
        $current_end_date = $this->addTwoWeeks($current_start_date);

        while ($current_start_date <= $end_date) {
            $weekRanges[] = $current_start_date . " - " . $current_end_date;
            $current_start_date = $this->addOneDay($current_end_date);
            $current_end_date = $this->addTwoWeeks($current_start_date);
        }
    }

    return $weekRanges;
}

public function showInvoices()
{
    $invoices = Invoice::all();  // Retrieve all invoices
    return view('admin.invoice', compact('invoices'));  // Pass the invoices to the view
}




// public function updateInvoiceStatus(Request $request,$id)
// {
//     $admin=Auth::user();
//     if(!admin){
//         return redirect()->route('login')->with('error','User session not found.PLease log in again.');
//     }

//     $invoice = Invoice::findOrFail($id);

//     $newStatus = $request->input('status');

//     switch($newStatus){
//         case 'Paid':
//             if($invoice->status !== 'Paid'){
//                 $invoice->status='Paid';
//                 $invoice->save();
//                 return redirect()->back()->with('success','Invoice marked as Paid successfully!');
//             }
//             break;

//             case 'Pending':
//                 $invoice->status='Pending';
//                 $invoice->save();
//                 return redirect()->back()->with('success','Invoice set to Pending');
//                 break;

//                 default:
//                 return redirect()->back()->with('error', 'Invalid status or invoice already in the selected status.');
//     }


//     }
// }



}


