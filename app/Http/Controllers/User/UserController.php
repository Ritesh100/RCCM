<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Leave;
use App\Models\Payslip;
use App\Models\RcUsers;
use App\Models\Timesheet;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function userDashboard()
    {
        $user = session()->has('userLogin');

        if ($user) {
            return view('user.dashboard');
        }
        return redirect('/');
    }

    public function showTimeSheet()
    {
        $user = session()->get('userLogin');

        if ($user) {
            $data = Timesheet::where('user_email', $user->email)->paginate(10);
            $reporting_to = $user->reportingTo;
            // $data = Timesheet::paginate(3); //for now 3 
            if ($data) {
                return view('user.user_timesheet', compact('data', 'reporting_to'));
            } else {
                return view('user.user_timesheet');
            }
        }
        return redirect('/');
    }

    public function storeTimeSheet(Request $request)
    {
        $user = session()->get('userLogin');
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

    public function showDocument()
    {


        $user = session()->get('userLogin');

        $document = Document::where('email', $user->email)->get();
        if ($document) {
            return view('user.document', compact('user', 'document'));
        }
        return view('user.document', ['user_email' => $user->email]);
    }

    public function storeDocument(Request $request)
    {
        $user = session()->get('userLogin');
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

    public function updateLeave()
    {
        $user = session()->get('userLogin');
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


    public function showPayslips()
    {
        $user = session()->get('userLogin');

        // Retrieve the user's timesheets ordered by date
        $timeSheets = Timesheet::where('user_email', $user->email)
            ->where('status', 'approved')
            ->orderBy('date', 'asc')
            ->get();

        foreach($timeSheets as $timesheet)
        {
            $reportingTo = $timesheet->reportingTo;
        }

        if ($timeSheets->isNotEmpty()) {

            $start_date = $timeSheets->first()->date;
            $end_date = $timeSheets->last()->date;
            // $dateRanges = [];

            $current_start_date = $start_date;
            $current_end_date = $this->addTwoWeeks($current_start_date);
            while (true) {
                $timeSheetInRange = DB::table('timesheets')
                    ->where('user_email', $user->email)
                    ->where('status', 'approved')
                    ->whereBetween('date', [$current_start_date, $current_end_date])
                    ->exists();


                if (!$timeSheetInRange) {
                    break;
                }

                $dateRanges[] = [
                    'start' => $current_start_date,
                    'end' => $current_end_date,
                ];

                //to next week range
                $current_start_date = $this->addOneDay($current_end_date);
                $current_end_date = $this->addTwoWeeks($current_start_date);
            }
        } else {
            $noDataMessage = "No payslips available. Please check back later or ensure you have submitted your timesheets.";
            return view('user.payslips', compact('noDataMessage'));
        }

        
        foreach($dateRanges as $dateRange)
        {
            $payslip = Payslip::where('week_range' , $dateRange['start'] . " - " . $dateRange['end'])->first();
            if(!$payslip)
            {
                Payslip::create([
                    'user_id' => $user->id,
                    'reportingTo' => $reportingTo,
                    'week_range' =>  $dateRange['start'] . " - " . $dateRange['end'],
                    'hrs_worked' => '0', //initially
                    'hrlyRate' => $user->hrlyRate,
                ]);
            }
        }
        return view('user.payslips', compact('dateRanges'));
    }

    public function generatePayslipsPdf(Request $request)
    {
        $user = session()->get('userLogin');
        $admin = User::first();
        

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

        $payslip->gross_earning = $gross_earning;
        $payslip->hrs_worked = $hrs_worked;
        $payslip->save();

        $pdf = Pdf::loadView('user.payslips_pdf', compact('user_address', 'hourly_rate', 'hrs_worked', 'abn', 'user_name', 'start_date', 'end_date','gross_earning'));

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
}
