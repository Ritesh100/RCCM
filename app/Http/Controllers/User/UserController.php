<?php

namespace App\Http\Controllers\User;
use App\Exports\UserTimesheetExport;
use Maatwebsite\Excel\Facades\Excel;
use DateTime;
use App\Models\Leave;
use App\Models\Payslip;
use App\Models\RcUsers;
use App\Models\Document;
use App\Models\Timesheet;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function userDashboard()
    {
        $user = session()->has('userLogin');

        if (!$user) {
            return redirect()->route('userLogin.form')->with('error', 'User session not found. Please log in again.');
        }

        if ($user) {
            return view('user.dashboard');
        }
        return redirect('/');
    }

    public function showTimeSheet(Request $request)
    {
        $user = session()->get('userLogin');
        if (!$user) {
            return redirect()->route('userLogin.form')->with('error', 'User session not found. Please log in again.');
        }
    
        // Initialize the query to filter timesheets by user's email
        $query = Timesheet::where('user_email', $user->email);
    
        // Apply filters if they are present in the request
        if ($request->filled('day')) {
            $query->where('day', $request->input('day'));
        }
    
        if ($request->filled('cost_center')) {
            $query->where('cost_center', $request->input('cost_center'));
        }
    
        if ($request->filled('date')) {
            $query->where('date', $request->input('date'));
        }
    
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
    
        $data = $query->paginate(10);
        $reporting_to = $user->reportingTo;
    
        // Fetch unique values for filters specific to the logged-in user
        $days = Timesheet::where('user_email', $user->email)->distinct()->pluck('day');
        $costCenters = Timesheet::where('user_email', $user->email)->distinct()->pluck('cost_center');
        $dates = Timesheet::where('user_email', $user->email)->distinct()->pluck('date')->sort();
    
        return view('user.user_timesheet', compact('data', 'reporting_to', 'days', 'costCenters', 'dates'));
    }
    
    
    
    public function storeTimeSheet(Request $request)
    {
        $user = session()->get('userLogin');
        if (!$user) {
            return redirect()->route('userLogin.form')->with('error', 'User session not found. Please log in again.');
        }
        // Loop through all the data for each day
        foreach ($request->input('date') as $key => $date) {
            // Create a new timesheet entry for each day
            Timesheet::create([
                'day' => $request->input('day')[$key], // e.g., 'Monday', 'Tuesday'
                'cost_center' => $request->input('cost_center')[$key],
                'currency' => $request->input('currency')[$key],
                'date' => $date,
                'start_time' => $request->input('start_time')[$key],
                'close_time' => $request->input('close_time')[$key],
                'break_start' => $request->input('break_start')[$key],
                'break_end' => $request->input('break_end')[$key],
                'timezone' => $request->input('timezone')[$key],
                'work_time' => $request->input('work_time')[$key],
                'user_email' => $user->email,
                'reportingTo' => $request->input('reportingTo')[$key]
            ]);
        }

        // Redirect after storing data
        return redirect()->route('user.timeSheet')->with('success', 'Timesheet saved successfully!');
    }

    public function showDocument(Request $request)
{
    $user = session()->get('userLogin');
    if (!$user) {
        return redirect()->route('userLogin.form')->with('error', 'User session not found. Please log in again.');
    }

    // Query to filter documents by document name if a search term is provided
    $document = Document::where('email', $user->email)
        ->when($request->has('search'), function ($query) use ($request) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        })
        ->get();

    return view('user.document', [
        'user' => $user,
        'document' => $document,
        'searchQuery' => $request->search
    ]);
}


    public function storeDocument(Request $request)
    {
        $user = session()->get('userLogin');
        if (!$user) {
            return redirect()->route('userLogin.form')->with('error', 'User session not found. Please log in again.');
        }
        $company_email = RcUsers::where('email', $user->email)->value('reportingTo');

        $validate = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'doc_file' => 'required|mimes:pdf,jpg,png,jpeg,doc,docx,xls,xlsx|max:2048'
        ]);

        if ($validate) {
            $path = $request->file('doc_file')->store('document', 'public');
            Document::create([
                'name' => $request->name,
                'email' => $request->email,
                'path' => $path,
                'reportingTo' => $company_email
            ]);

            return redirect()->back()->with('success', 'File stored');
        }
    }

    public function showProfile()
    {
        $user = session()->get('userLogin');
        if (!$user) {
            return redirect()->route('userLogin.form')->with('error', 'User session not found. Please log in again.');
        }
        return view('user.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|', 
            'password' => 'nullable|string|min:3|confirmed',
            'companyName' => 'nullable|string|max:255',

            'reportingTo' => 'required|email',
            'address' => 'nullable|string|max:255',
            'contact' => 'nullable|string|max:255',
            'hrlyRate' => 'required|numeric',
            'currency' => 'required|string',


        ]);
        $user = session()->get('userLogin');

        if (!$user) {
            return redirect()->route('userLogin.form')->with('error', 'User session not found. Please log in again.');
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->reportingTo = $request->reportingTo;
        $user->address = $request->address;
        $user->contact = $request->contact;
        $user->hrlyRate = $request->hrlyRate;
        $user->currency = $request->currency; 

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('user.profile')->with('success', 'Profile updated successfully.');
    }
   
    public function updateLeave()
    {
        $user = session()->get('userLogin');
        if (!$user) {
            return redirect()->route('userLogin.form')->with('error', 'User session not found. Please log in again.');
        }
        $timeSheets = Timesheet::where('user_email', $user->email)
            ->where('status', 'approved')
            ->get();
        $leave = Leave::where('user_id', $user->id)->first();

        $totalSickLeave = $leave->total_sick_leave;
        $totalPublicHoliday = $leave->total_public_holiday;

        $totalAnnualLeave = 0;
        foreach ($timeSheets as $timeSheet) {
            if ($timeSheet->cost_center == 'hrs_worked') {
                // Convert work_time from 'HH:MM:SS' to decimal hours
                $workTimeParts = explode(':', $timeSheet->work_time); // Split the time into hours, minutes, and seconds
                $hours = (int)$workTimeParts[0]; // Get hours
                $minutes = (int)$workTimeParts[1]; // Get minutes

                // Convert total time to decimal hours
                $decimalHours = $hours + ($minutes / 60); // Convert minutes to hours and add

                // Calculate total annual leave for this entry
                $totalAnnualLeave += $decimalHours * 0.073421; // Multiply by the rate
            }
        }
        $leave->total_annual_leave = $totalAnnualLeave;


        $takenAnnualLeave = 0;
        foreach ($timeSheets as $timeSheet) {
            if ($timeSheet->cost_center == 'annual_leave') {
                // Convert work_time from 'HH:MM:SS' to decimal hours
                $workTimeParts = explode(':', $timeSheet->work_time); // Split the time into hours, minutes, and seconds
                $hours = (int)$workTimeParts[0]; // Get hours
                $minutes = (int)$workTimeParts[1]; // Get minutes

                // Convert total time to decimal hours
                $decimalHours = $hours + ($minutes / 60); // Convert minutes to hours and add

                // Calculate taken annual leave for this entry
                $takenAnnualLeave += $decimalHours * 0.073421;
            }
        }
        $leave->annual_leave_taken = $takenAnnualLeave;

        $remaining_annual_leave = $totalAnnualLeave - $takenAnnualLeave;


        $sickLeaveCount = 0;
        $annualLeaveCount = 0;

        // Handle sick leave from the 'cost_center' column for each timesheet
        foreach ($timeSheets as $timeSheet) {
            if (!empty($timeSheet->cost_center)) {
                $costCenters = explode(',', $timeSheet->cost_center); // Change the delimiter as necessary
                $sickLeaveCount += array_count_values($costCenters)['sick_leave'] ?? 0; // Count sick leave
                $leave->sick_leave_taken = $sickLeaveCount;
            }
        }

        $remaining_sick_leave = $leave->total_sick_leave - $sickLeaveCount;

        $publicHolidayCount = 0;
        // Handle public holiday from the 'cost_center' column for each timesheet
        foreach ($timeSheets as $timeSheet) {
            if (!empty($timeSheet->cost_center)) {
                $costCenters = explode(',', $timeSheet->cost_center); // Change the delimiter as necessary
                $publicHolidayCount += array_count_values($costCenters)['public_holiday'] ?? 0; // Count sick leave
                $leave->public_holiday_taken = $publicHolidayCount;
            }
        }

        $leave->save();

        $remaining_public_holiday = $leave->total_public_holiday - $publicHolidayCount;


        return view('user.leave', compact('remaining_sick_leave', 'totalSickLeave', 'sickLeaveCount', 'totalAnnualLeave', 'takenAnnualLeave', 'remaining_annual_leave', 'totalPublicHoliday', 'remaining_public_holiday', 'publicHolidayCount'));
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
        $user = session()->get('userLogin');
        
        // Check if the user session exists
        if (!$user) {
            return redirect()->route('userLogin.form')->with('error', 'User session not found. Please log in again.');
        }
    
        // Retrieve the user's approved timesheets ordered by date
        $timeSheets = Timesheet::where('user_email', $user->email)
            ->where('status', 'approved')
            ->orderBy('date', 'asc')
            ->get();
    
        // Initialize the array to store date ranges
        $dateRanges = [];
    
        if ($timeSheets->isNotEmpty()) {
            $start_date = $timeSheets->first()->date;
            $end_date = $timeSheets->last()->date;
    
            $current_start_date = $start_date;
            $current_end_date = $this->addTwoWeeks($current_start_date);
    
            while (true) {
                // Check if there are approved timesheets in the current date range
                $timeSheetsInRange = Timesheet::where('user_email', $user->email)
                    ->where('status', 'approved')
                    ->whereBetween('date', [$current_start_date, $current_end_date])
                    ->get();
    
                if ($timeSheetsInRange->isEmpty()) {
                    break;
                }
    
                // Calculate hours worked using the new method
                $hoursWorked = $this->calculateHoursWorked($timeSheetsInRange);
    
                // Store the date range and hours worked
                $dateRanges[] = [
                    'start' => $current_start_date,
                    'end' => $current_end_date,
                    'hours' => $hoursWorked // This is already formatted
                ];
    
                // Create or update payslip record
                $weekRange = $current_start_date . " - " . $current_end_date;
                $payslip = Payslip::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'week_range' => $weekRange,
                    ],
                    [
                        'reportingTo' => $timeSheetsInRange->first()->reportingTo, // Assuming reportingTo is in the timesheet
                        'hrs_worked' => $hoursWorked,
                        'hrlyRate' => $user->hrlyRate,
                    ]
                );
    
                // Move to the next week range
                $current_start_date = $this->addOneDay($current_end_date);
                $current_end_date = $this->addTwoWeeks($current_start_date);
            }
        } else {
            // If no timesheets are available
            $noDataMessage = "No payslips available. Please check back later or ensure you have submitted your timesheets.";
            return view('user.payslips', compact('noDataMessage'));
        }
    
        return view('user.payslips', compact('dateRanges'));
    }
    


    public function generatePayslipsPdf(Request $request)
    {
        $user = session()->get('userLogin');
        if (!$user) {
            return redirect()->route('userLogin.form')->with('error', 'User session not found. Please log in again.');
        }

        $admin = User::first();
        if (!$user) {
            return redirect()->back()->with('error', 'User session not found.');
        }
    

        $start_date = $request->start;
        $end_date = $request->end;

        $payslip = Payslip::where('week_range', $start_date . " - " . $end_date)->first();

        $timeSheets = Timesheet::where('user_email', $user->email)
            ->where('status', 'approved')
            ->where('cost_center', 'hrs_worked')
            ->whereBetween('date', [$start_date, $end_date])
            ->get();

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

        // Format the result to 2 decimal places formatted hours
        $hrs_worked = number_format($total_hours_decimal, 2);

        $user_name = $user->name;
        $user_address = $user->address;
        $abn = $admin->abn;
        $hourly_rate = $user->hrlyRate;
        $gross_earning = $hourly_rate * $hrs_worked;
        $annual_leave = 0.073421 * $hrs_worked;
        $currency = $user->currency ?? 'NPR'; 

        $payslip->gross_earning = $gross_earning;
        $payslip->hrs_worked = $hrs_worked;
        $payslip->save();

        $pdf = Pdf::loadView('user.payslips_pdf', compact('user_address', 'hourly_rate', 'hrs_worked', 'abn', 'user_name', 'start_date', 'end_date','gross_earning','annual_leave','currency'));

        return $pdf->stream("payslips_{$start_date}_to_{$end_date}.pdf");
    }

    //add 15 days
    private function addTwoWeeks($starting_date)
    {
        // Convert the database date to a DateTime object
        $date = new DateTime($starting_date);

        // Add two weeks to the date
        $date->modify('+15 days');

        // Return the new date in the same format as the database
        return $date->format('Y-m-d');
    }

    private function addOneDay($starting_date)
    {
        // Convert the database date to a DateTime object
        $date = new DateTime($starting_date);

        // Add two weeks to the date
        $date->modify('+1 day');

        // Return the new date in the same format as the database
        return $date->format('Y-m-d');
    }
    public function exportApproved()
{
    $user = session()->get('userLogin');
    return (new UserTimesheetExport('approved', $user->email))
        ->download('approved_timesheets.xlsx');
}

public function exportPending()
{
    $user = session()->get('userLogin');
    return (new UserTimesheetExport('pending', $user->email))
        ->download('pending_timesheets.xlsx');
}

public function exportAll()
{
    $user = session()->get('userLogin');
    return (new UserTimesheetExport(null, $user->email))
        ->download('all_timesheets.xlsx');
}
}

