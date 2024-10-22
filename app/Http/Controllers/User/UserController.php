<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Leave;
use App\Models\RcUsers;
use App\Models\Timesheet;
use Illuminate\Http\Request;

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
        if($document)
        {
            return view('user.document',compact('user', 'document'));

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
}
