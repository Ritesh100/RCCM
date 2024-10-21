@extends('user.sidebar')

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
                <th>Leave Type</th>
                <th>Total Leaves</th>
                <th>Leaves Taken</th>
                <th>Remaining Leaves</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Sick Leave</td>
                <td>{{ $totalSickLeave}}</td>
                <td>{{ $sickLeaveCount}}</td>
                <td>{{ $remaining_sick_leave}}</td>
            </tr>
            <tr>
                <td>Annual Leave</td>
                <td>{{ $totalAnnualLeave}}</td>
                <td>{{ $takenAnnualLeave}}</td>
                <td>{{ $remaining_annual_leave}}</td>
                
            </tr>
        </tbody>
    </table>
</div>

@endsection