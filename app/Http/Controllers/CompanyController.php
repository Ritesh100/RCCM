<?php

namespace App\Http\Controllers;
use DateTime;
use App\Models\Leave;
use App\Models\RcUsers;
use App\Models\Timesheet;
use Log;
use App\Models\Company;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Payslip;
use Illuminate\Support\Facades\DB;
use App\Exports\CompanyTimesheetExport;
use App\Models\Invoice;
use Maatwebsite\Excel\Facades\Excel;
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
            return redirect()->route('companyLogin')->with('error', 'Company not found!');
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
            return redirect()->route('companyLogin')->with('error', 'You must be logged in to access this page.');
        }

        // Pass the company data to the view
        return view('company.profile', compact('company'));
    }

    public function getUsers()
    {
        $company = session()->get('company');
        if (!$company) {
            return redirect()->route('companyLogin')->with('error', 'You must be logged in to access this page.');
        }
    
        $company_user = $company->email;
    
        // Get the search query from the request
        $searchQuery = request('search');
    
        // Build the query for users based on reportingTo and search term
        $users = RcUsers::where('reportingTo', $company_user)
                    ->when($searchQuery, function ($query, $searchQuery) {
                        return $query->where('name', 'like', '%' . $searchQuery . '%'); // Search only by name
                    })
                    ->get();
    
        return view('company.users', compact('users'));
    }
    
    
    public function showTimeSheet(Request $request)
{
    // Get the company from the session
    $company = session()->get('company');
    if (!$company) {
        return redirect()->route('companyLogin')->with('error', 'You must be logged in to access this page.');
    }

    // Fetch all users reporting to the company
    $company_users = RcUsers::where('reportingTo', $company->email)->get();

    // Extract unique fields for filtering
    $uniqueUsernames = $company_users->pluck('name')->unique();
    $uniqueDays = Timesheet::whereIn('user_email', $company_users->pluck('email'))->pluck('day')->unique();
    $uniqueCostCenters = Timesheet::whereIn('user_email', $company_users->pluck('email'))->pluck('cost_center')->unique();
    $uniqueStatuses = Timesheet::whereIn('user_email', $company_users->pluck('email'))->pluck('status')->unique();
    $uniqueDates = Timesheet::whereIn('user_email', $company_users->pluck('email'))->pluck('date')->unique();

    // Check if a search query is provided
    $searchQuery = $request->input('search');
    
    // Fetch the users again if a search query is provided
    if ($searchQuery) {
        // Filter the company_users by name based on the search query
        $company_users = $company_users->where('name', 'LIKE', "%{$searchQuery}%");
    }

    // Extract emails of the filtered users
    $userEmails = $company_users->pluck('email')->toArray();

    // Prepare the Timesheet query
    $timesheetQuery = Timesheet::whereIn('user_email', $userEmails);


    // Check for additional filters
    if ($request->filled('username')) {
        $timesheetQuery->where('user_email', $request->input('username'));
    }
    if ($request->filled('day')) {
        $timesheetQuery->where('day', $request->input('day'));
    }
    if ($request->filled('cost_center')) {
        $timesheetQuery->where('cost_center', $request->input('cost_center'));
    }
    if ($request->filled('status')) {
        $timesheetQuery->where('status', $request->input('status'));
    }
    if ($request->filled('date')) {
        $timesheetQuery->where('date', $request->input('date'));
    }

    // Paginate the filtered timesheet data
    $users = $timesheetQuery->paginate(10);

    // Map user emails to their corresponding names from $company_users
    $users->each(function ($user) use ($company_users) {
        $user->name = $company_users->firstWhere('email', $user->user_email)->name ?? 'N/A';
    });

    // Return the view with the paginated timesheet data and filtering options
    return view('company.timesheet', compact('users', 'searchQuery', 'uniqueUsernames', 'uniqueDays', 'uniqueCostCenters', 'uniqueStatuses', 'uniqueDates'));
}

    
    


    public function updateStatus(Request $request, $id)
    {
        $company = session()->get('company');
        if (!$company) {
            return redirect()->route('companyLogin')->with('error', 'You must be logged in to access this page.');
        }
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
    public function showDocument()
    {
        $company = session()->get('company');
        if (!$company) {
            return redirect()->route('companyLogin')->with('error', 'You must be logged in to access this page.');
        }
    
        // Get the search query from the request
        $searchQuery = request('search');
    
        // Build the query for documents based on reportingTo and the search term
        $documents = Document::where('reportingTo', $company->email)
            ->when($searchQuery, function ($query, $searchQuery) {
                return $query->where('name', 'like', '%' . $searchQuery . '%'); // Search by document name
            })
            ->get();
            // if(Storage::exists($documents->path))
        // {
        //     $download = Storage::download($documents->path);
        // }
    
        return view('company.document', compact('documents'));
    }
    
 

    public function showLeave(Request $request)
    {
        $company = session()->get('company');
        if (!$company) {
            return redirect()->route('companyLogin')->with('error', 'You must be logged in to access this page.');
        }
        $user = RcUsers::where('reportingTo', $company->email)->get();
        $user_id = $user->pluck('id')->toArray();

        $searchName = $request->searchName;
        $leaves = Leave::with('rcUser')
            ->whereIn('user_id',$user_id)
            ->whereHas('rcUser', function ($query) use ($searchName){
                $query->where('name', 'LIKE', '%' . $searchName . '%');
            })
            ->get();

        return view('company.leave',compact('leaves'));
        
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

                // Convert to total minutes
                $totalMinutes += ($hours * 60) + $minutes + ($seconds / 60);
            }
        }

        // Convert total minutes back to hours and minutes
        $hour_worked = floor($totalMinutes / 60);
        $minutes_worked = $totalMinutes % 60;

        // Convert total time to decimal hours
        $total_hours_decimal = $hour_worked + ($minutes_worked / 60);

        // Format the result to 2 decimal places
        return number_format($total_hours_decimal, 2);
    }

    public function showPayslips(Request $request)
    {
        $company = session()->get('company');
        if (!$company) {
            return redirect()->route('companyLogin')->with('error', 'You must be logged in to access this page.');
        }
    
        // Get all users reporting to this company
        $usersQuery = RcUsers::where('reportingTo', $company->email);
    
        // Apply filters based on request parameters
        if ($request->filled('search')) {
            $searchQuery = $request->input('search');
            $usersQuery->where(function ($query) use ($searchQuery) {
                $query->where('name', 'like', "%{$searchQuery}%")
                      ->orWhere('email', 'like', "%{$searchQuery}%");
            });
        }
    
        if ($request->filled('username')) {
            $username = $request->input('username');
            $usersQuery->where('name', $username);
        }
    
        if ($request->filled('useremail')) {
            $useremail = $request->input('useremail');
            $usersQuery->where('email', $useremail);
        }
    
        $users = $usersQuery->get();
    
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
                    // Get timesheets for the current date range
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
                    Payslip::updateOrCreate(
                        [
                            'user_id' => $user->id,
                            'week_range' => $weekRange,
                        ],
                        [
                            'reportingTo' => $company->email,
                            'hrs_worked' => $hoursWorked,
                            'hrlyRate' => $user->hrlyRate,
                        ]
                    );
    
                    // Move to the next date range
                    $current_start_date = $this->addOneDay($current_end_date);
                    $current_end_date = $this->addTwoWeeks($current_start_date);
                }
    
                $userPayslips[$user->id] = [
                    'user' => $user,
                    'dateRanges' => $dateRanges
                ];
            }
        }
    
        // Get unique usernames and emails for filter dropdowns
        $uniqueUsernames = RcUsers::where('reportingTo', $company->email)->pluck('name')->unique();
        $uniqueUseremails = RcUsers::where('reportingTo', $company->email)->pluck('email')->unique();
    
        return view('company.payslips', compact('userPayslips', 'uniqueUsernames', 'uniqueUseremails'));
    }
    


    public function generatePayslip($userId, $weekRange)
{
    $company = session()->get('company');
    if (!$company) {
        return redirect()->route('companyLogin')->with('error', 'You must be logged in to access this page.');
    }
    // Verify the user belongs to this company
    $user = RcUsers::where('id', $userId)
        ->where('reportingTo', $company->email)
        ->firstOrFail();
    
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

            // Convert to total minutes
            $totalMinutes += ($hours * 60) + $minutes + ($seconds / 60);
        }
    }

    // Convert total minutes back to hours and minutes
    $hour_worked = floor($totalMinutes / 60);
    $minutes_worked = $totalMinutes % 60;

    // Convert total time to decimal hours
    $total_hours_decimal = $hour_worked + ($minutes_worked / 60);

    // Format the result to 2 decimal places formatted hours
    $hrs_worked = number_format($total_hours_decimal, 2);

    // Calculate gross earning
    $hourly_rate = $user->hrlyRate;
    $gross_earning = $hourly_rate * $hrs_worked;
    $annual_leave = 0.073421 * $hrs_worked;

    $currency = $user->currency ?? 'NPR';

    // Update payslip details
    $payslip->hrs_worked = $hrs_worked;
    $payslip->gross_earning = $gross_earning;
    $payslip->save();

    // Prepare data for the PDF
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
    $pdf = PDF::loadView('company.payslips_pdf', $data);
    
    // Set paper size and orientation
    $pdf->setPaper('a4', 'portrait');
    
    // Generate filename
    $filename = 'payslip_' . $user->name . '_' . str_replace(' ', '_', $weekRange) . '.pdf';
    
    // Return the PDF as a download
    return $pdf->stream($filename);
}


private function addTwoWeeks($starting_date)
{
    $company = session()->get('company');
    if (!$company) {
        return redirect()->route('companyLogin')->with('error', 'You must be logged in to access this page.');
    }
    // Convert the database date to a DateTime object
    $date = new DateTime($starting_date);
    
    // Add two weeks (15 days) to the date
    $date->modify('+15 days');
    
    // Return the new date in the same format as the database
    return $date->format('Y-m-d');
}

private function addOneDay($starting_date)
{
    $company = session()->get('company');
    if (!$company) {
        return redirect()->route('companyLogin')->with('error', 'You must be logged in to access this page.');
    }
    // Convert the database date to a DateTime object
    $date = new DateTime($starting_date);
    
    // Add one day to the date
    $date->modify('+1 day');
    
    // Return the new date in the same format as the database
    return $date->format('Y-m-d');
}
public function exportTimesheets($status = null)
{
    $company = session()->get('company');
    
    if (!$company) {
        return redirect()->route('companyLogin');
    }

    $filename = 'Company_Timesheet';
    if ($status) {
        $filename .= '_' . ucfirst($status);
    }
    $filename .= '_' . date('Y-m-d') . '.xlsx';

    return Excel::download(new CompanyTimesheetExport($status, $company->email), $filename);
}
public function showInvoice(Request $request)
{
    // Retrieve the company ID from the session
    $company = session()->get('company');
    
    if (!$company) {
        return redirect()->route('companyLogin');
    }
    $companyName = $company->name; // Assuming user has a `company` relationship


    // Filter invoices by the company ID
    $invoices = Invoice::where('invoice_for', $companyName)->get();

    // Retrieve the search query from the request
    $searchQuery = $request->input('search');

    // Filter invoices based on the search query if present
    $invoices = $invoices->when($searchQuery, function ($query, $searchQuery) {
        return $query->where('invoice_for', 'LIKE', '%' . $searchQuery . '%');
    });

    return view('company.invoice', compact('invoices', 'searchQuery'));
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
        $credit = $invoice->previous_credits + $invoice->total_charge - $invoice->total_transferred;
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
        'credit' => $credit,
        'issued_on' => $issued_on,
        'address' => $address,
        'admin_abn' => $admin->abn,
        'admin_address' => $admin->address,
        'images' => $images // Pass the images to the view
    ]);
    
    return $pdf->stream();
}

}



  
