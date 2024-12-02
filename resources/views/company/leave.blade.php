@extends('company.sidebar')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
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


<div class="containe-fluid">
    <h1 class="mb-4 text-center">Leave Dashboard</h1>

<div class="d-flex justify-content-center mt-4 mb-4">

    <form action="{{ route('company.leave') }}" method="GET" class="input-group" style="max-width: 600px;">
        <input type="text"  name="searchName" 
        id="name" 
        class="form-control  rounded-pill" 
        placeholder="Search by User name"
        value="{{ request('searchName') }}">
        <button type="submit" class="btn btn-primary rounded-pill ms-2">Search</button>
        <button type="button" class="btn btn-secondary rounded-pill ms-2" onClick="window.location.href='{{ route('company.leave') }}' ">Reset </button>
    </form>
</div>

        <div class="table-responsive shadow-lg mt-4"> <!-- Added shadow-lg for a shadow effect -->
            <table class="table table-hover table-striped table-borderless align-middle w-100"> <!-- Full width with w-100 -->
                <thead class="text-black">
                    <tr>
                    <th>Name</th>
                    <th>Leave Type</th>
                    <th>Total Leaves (Hr)</th>
                    <th>Leaves Taken (Hr)</th>
                    <th>Remaining Leaves  (Hr)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($leaves as $leave)
                    <tr>
                        <td rowspan="4">{{ $leave->rcUser->name }}</td>
                        <td>Sick Leave</td>
                        <td>{{ $leave->total_sick_leave}}</td>
                        <td>{{ $leave->sick_leave_taken}}</td>
                        <td>{{ $leave->total_sick_leave - $leave->sick_leave_taken }}</td>
                    </tr>
                    <tr>
                        <td>Annual Leave</td>
                        <td>{{ $leave->total_annual_leave  }}</td>
                        <td>{{ $leave->annual_leave_taken }}</td>
                        <td>{{ $leave->total_annual_leave - $leave->annual_leave_taken }}</td>
                    </tr>
                    <tr>
                        <td>Public Holiday</td>
                        <td>{{ $leave->total_public_holiday }}</td>
                        <td>{{ $leave->public_holiday_taken }}</td>
                        <td>{{ $leave->total_public_holiday - $leave->public_holiday_taken }}</td>
                    </tr>
                    <tr>
                        <td>Unpaid Leave</td>
                        <td>{{ $leave->total_unpaid_leave }}</td>
                        <td>{{ $leave->taken_unpaid_leave }}</td>
                        <td>{{ $leave->total_unpaid_leave - $leave->taken_unpaid_leave}}</td>
                    </tr>
                    <tr><td colspan="5" style="height: 10px;"></td></tr>

                    
                @endforeach


            </tbody>
  

        </table>
    </div>

@endsection
