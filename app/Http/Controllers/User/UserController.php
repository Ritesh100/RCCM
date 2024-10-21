<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Document;
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
            $data = Timesheet::where('user_email',$user->email)->paginate(3);
            $reporting_to = $user->reportingTo;
            // $data = Timesheet::paginate(3); //for now 3 
            if ($data) {
                return view('user.user_timesheet', compact('data','reporting_to'));
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

    public function showDocument() {


    $user = session()->get('userLogin');

    $document = Document::where('email', $user->email)->get();
        if($document)
        {
            return view('user.document',compact('user', 'document'));
        }
        return view('user.document',['user_email'=>$user->email]);
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

        if($validate)
        {
            $path = $request->file('doc_file')->store('document', 'public');
            Document::create([
                'name' => $request->name,
                'email' => $request->email,
                'path' => $path,
                'reportingTo' => $company_email
            ]);

            return redirect()->back()->with('success','File stored');
        }
    }
}
