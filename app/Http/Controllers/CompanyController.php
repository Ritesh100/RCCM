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
     // Helper method to get fresh company data
     private function getFreshCompanyData()
     {
         $sessionCompany = session()->get('company');
         if (!$sessionCompany) {
             return null;
         }

         // Get fresh company data from database
         // Note: Replace 'Company' with your actual company model name if different
         $freshCompany = Company::where('email', $sessionCompany->email)->first();
         if ($freshCompany) {
             // Update session with fresh data
             session()->put('company', $freshCompany);
             return $freshCompany;
         }

         return $sessionCompany;
     }


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
        $company = $this->getFreshCompanyData();
        if (!$company) {
            return redirect()->route('companyLogin')->with('error', 'You must be logged in to access this page.');
        }

        // Pass the company data to the view
        return view('company.profile', compact('company'));
    }

    public function getUsers()
    {
        $company = $this->getFreshCompanyData();
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

        // Filter the company_users by name based on the search query
        if ($searchQuery) {
            $company_users = $company_users->where('name', 'LIKE', "%{$searchQuery}%");
        }

        // Extract emails of the filtered users
        $userEmails = $company_users->pluck('email')->toArray();

        // Base query builder
        $baseQuery = Timesheet::whereIn('user_email', $userEmails);

        // Apply filters to the base query
        if ($request->filled('username')) {
            $baseQuery->where('user_email', $request->input('username'));
        }
        if ($request->filled('day')) {
            $baseQuery->where('day', $request->input('day'));
        }
        if ($request->filled('cost_center')) {
            $baseQuery->where('cost_center', $request->input('cost_center'));
        }
        if ($request->filled('date')) {
            $baseQuery->where('date', $request->input('date'));
        }

        // Create separate queries for pending and approved timesheets
        $pendingTimesheets = (clone $baseQuery)
            ->where('status', 'pending')
            ->paginate(10, ['*'], 'pending_page');

        $approvedTimesheets = (clone $baseQuery)
            ->where('status', 'approved')
            ->paginate(10, ['*'], 'approved_page');

        // Map user emails to their corresponding names
        $mapNames = function ($timesheet) use ($company_users) {
            $timesheet->name = $company_users->firstWhere('email', $timesheet->user_email)->name ?? 'N/A';
            return $timesheet;
        };

        $pendingTimesheets->each($mapNames);
        $approvedTimesheets->each($mapNames);

        return view('company.timesheet', compact(
            'pendingTimesheets',
            'approvedTimesheets',
            'searchQuery',
            'uniqueUsernames',
            'uniqueDays',
            'uniqueCostCenters',
            'uniqueStatuses',
            'uniqueDates'
        ));
    }

    public function bulkUpdate(Request $request)
    {
        $timesheetIds = explode(',', $request->input('timesheet_ids'));
        $status = $request->input('status');

        if ($status === 'delete') {
            // Delete the selected records
            Timesheet::whereIn('id', $timesheetIds)->delete();

            return redirect()->route('company.timeSheet')
                             ->with('success', 'Selected timesheets have been deleted successfully.');
        }

        // Handle other status updates (approved, pending) as usual
        if ($status === 'approved') {
            Timesheet::whereIn('id', $timesheetIds)
                     ->update(['status' => 'approved']);
        } elseif ($status === 'pending') {
            Timesheet::whereIn('id', $timesheetIds)
                     ->update(['status' => 'pending']);
        }

        return redirect()->route('company.timeSheet')
                         ->with('success', 'Selected timesheets have been updated successfully.');
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

        // Get all users for the name dropdown
        $users = RcUsers::where('reportingTo', $company->email)->get();
        $user_id = $users->pluck('id')->toArray();

        $searchName = $request->searchName;
        $leaveType = $request->leaveType;

        $leaves = Leave::with('rcUser')
            ->whereIn('user_id', $user_id);

        if ($searchName) {
            $leaves->whereHas('rcUser', function ($query) use ($searchName) {
                $query->where('name', $searchName);  // Changed from LIKE to exact match since we're using dropdown
            });
        }

        $leaves = $leaves->get();

        // Get unique leave types for dropdown
        $leaveTypes = ['Sick Leave', 'Annual Leave', 'Public Holiday', 'Unpaid Leave'];

        return view('company.leave', compact('leaves', 'leaveTypes', 'users'));
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
                        ->whereBetween('date', [$current_start_date, $current_end_date])
                        ->where('status', 'approved')
                        ->get();

                    if ($timeSheetsInRange->isEmpty()) {
                        break;
                    }
                    $leave = Leave::where('user_id', $user->id)->first();


                    // Fetch active payslip
                    $payslip = Payslip::where('user_id', $user->id)
                        ->where('week_range', $current_start_date . " - " . $current_end_date)
                        ->where('status', 'active')
                        ->where('disable', false) // Ensure disable is false

                        ->first();

                    if (!$payslip) {
                        // Skip this range if no active payslip exists
                        $current_start_date = $this->addOneDay($current_end_date);
                        $current_end_date = $this->addTwoWeeks($current_start_date);
                        continue;
                    }

                    // Calculate hours worked for approved timesheets
                    $hoursWorked = $this->calculateHoursWorked($timeSheetsInRange, $leave);

                    $dateRanges[] = [
                        'start' => $current_start_date,
                        'end' => $current_end_date,
                        'hours' => $hoursWorked,
                        'status' => 'active', // Explicitly mark as active
                    ];

                    // Move to the next range
                    $current_start_date = $this->addOneDay($current_end_date);
                    $current_end_date = $this->addTwoWeeks($current_start_date);
                }

                $userPayslips[$user->id] = [
                    'user' => $user,
                    'dateRanges' => $dateRanges
                ];
            }
        }

        // Fetch unique usernames and emails for dropdowns
        $uniqueUsernames = RcUsers::where('reportingTo', $company->email)->pluck('name')->unique();
        $uniqueUseremails = RcUsers::where('reportingTo', $company->email)->pluck('email')->unique();

        // Return view with filtered payslip data
        return view('company.payslips', compact('userPayslips', 'uniqueUsernames', 'uniqueUseremails'));
    }



    public function generatePayslip($userId, $weekRange)
    {
        $company = session()->get('company');
        if (!$company) {
            return redirect()->route('companyLogin')->with('error', 'You must be logged in to access this page.');
        }
        $admin = Auth::user();

        $user = RcUsers::where('id', $userId)
            ->where('reportingTo', $company->email)
            ->firstOrFail();

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

        $pdf = PDF::loadView('company.payslips_pdf', $data);
        $pdf->setPaper('a4', 'portrait');

        $filename = 'payslip_' . $user->name . '_' . str_replace(' ', '_', $weekRange) . '.pdf';

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
        $previousCredit = json_decode($invoice->previous_credits);
        $accumulatedCredit = $invoice->total_transferred -  $invoice->total_charge ;
        $credit = $invoice->previous_credits   + ( $invoice->total_transferred -  $invoice->total_charge);         $issued_on = $invoice->created_at;
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
        'admin_address' => $admin->address,
        'images' => $images, // Pass the images to the view,
        'admin' => $admin
    ]);

    return $pdf->stream();
}
public function privacy(){

    return view('company.privacy');
}

}



