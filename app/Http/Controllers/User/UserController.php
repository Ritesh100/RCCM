<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
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
        $user = session()->has('userLogin');

        if ($user) {
            $data = Timesheet::paginate(3); //for now 3 
            if ($data) {
                return view('user.user_timesheet', compact('data'));
            } else {
                return view('user.user_timesheet');
            }
        }
        return redirect('/');
    }

    public function storeTimeSheet(Request $request)
    {
        // Loop through all the data for each day
        foreach ($request->input('date') as $key => $date) {
            // Create a new timesheet entry for each day
            Timesheet::create([
                'day' => $request->input('day')[$key], // e.g., 'Monday', 'Tuesday'
                'cost_center' => $request->input('cost_center')[$key],
                'date' => $date,
                'start_time' => $request->input('start_time')[$key],
                'close_time' => $request->input('close_time')[$key],
                'break_start' => $request->input('break_start')[$key],
                'break_end' => $request->input('break_end')[$key],
                'timezone' => $request->input('timezone')[$key],
            ]);
        }

        // Redirect after storing data
        return redirect()->route('user.timeSheet')->with('success', 'Timesheet saved successfully!');
    }

    public function getTimeSheet() {}
}
