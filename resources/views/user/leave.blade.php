@extends('user.sidebar')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<style>
    .custom-header {
        background: linear-gradient(to right, #6c757d, #adb5bd);
        color: white;
    }

    .custom-header th {
        padding: 5px;
        text-align: center;
    }
</style>
<div class="container-fluid">
    <h1 class="mb-4 text-center">Leave Dashboard</h1>

<div class="table-responsive shadow-lg mt-4"> <!-- Added shadow-lg for a shadow effect -->
    <table class="table table-hover table-striped table-borderless align-middle w-100"> <!-- Full width with w-100 -->
        <thead class="text-black">
            <tr>
                <th>Leave Type</th>
                <th>Total Leaves (Hr)</th>
                <th>Leaves Taken (Hr)</th>
                <th>Remaining Leaves (Hr)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Sick Leave(10 days)</td>
                <td>{{ $totalSickLeave  }}</td>
                <td>{{ $sickLeaveCount }}</td>
                <td>{{ $remaining_sick_leave  }}</td>
            </tr>
            <tr>
                <td>Annual Leave</td>
                <td>{{ $totalAnnualLeave}}</td>
                <td>{{ $takenAnnualLeave}}</td>
                <td>{{ $remaining_annual_leave}}</td>
                
            </tr>
            <tr>
                <td>Public Holiday(13 days)</td>
                <td>{{ $totalPublicHoliday }}</td>
                <td>{{$publicHolidayCount}}</td>
                <td>{{$remaining_public_holiday }}</td>
                
            </tr>
            <tr>
                <td>Unpaid Leave(0 day)</td>
                <td>{{ $totalUnpaidLeave }}</td>
                <td>{{$unpaidLeaveCount }}</td>
                <td>{{$remaining_unpaid_leave}}</td>
                
            </tr>
        </tbody>
    </table>
</div>
</div>


@endsection