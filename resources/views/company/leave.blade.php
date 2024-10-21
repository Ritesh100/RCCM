@extends('company.sidebar')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 20px;
        background-color: #f9f9f9;
    }
    .container {
        max-width: 600px;
        margin: auto;
        padding: 20px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    h1 {
        text-align: center;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    th, td {
        padding: 10px;
        border: 1px solid #ccc;
        text-align: center;
    }
    th {
        background-color: #f2f2f2;
    }
</style>

<div class="container">
    <h1>Leave Dashboard</h1>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Leave Type</th>
                <th>Total Leaves</th>
                <th>Leaves Taken</th>
                <th>Remaining Leaves</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($leaves as $leave)
            <tr>
                <td rowspan="3">{{ $leave->rcUser->name }}</td>
                <td>Sick Leave</td>
                <td>{{$leave->total_sick_leave}}</td>
                <td>{{$leave->sick_leave_taken}}</td>
                <td>{{$leave->total_sick_leave - $leave->sick_leave_taken}}</td>
            </tr>
            <tr>
                <td>Annual Leave</td>
                <td>{{$leave->total_annual_leave}}</td>
                <td>{{$leave->annual_leave_taken}}</td>
                <td>{{$leave->total_annual_leave - $leave->annual_leave_taken}}</td>
            </tr>
            <tr>
                <td>Public Holiday</td>
                <td>{{$leave->total_public_holiday}}</td>
                <td>{{$leave->public_holiday_taken}}</td>
                <td>{{$leave->total_public_holiday - $leave->public_holiday_taken}}</td>
            </tr>
            @endforeach
            
        </tbody>
    </table>
</div>

@endsection