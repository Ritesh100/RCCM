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




<div class="d-flex justify-content-center mt-4 mb-4">

    <form action="{{ route('company.leave') }}" method="GET" class="input-group" style="max-width: 600px;">
        <input type="text"  name="searchName" 
        id="name" 
        class="form-control  rounded-pill" 
        placeholder="Enter name"
        value="{{ request('searchName') }}">
        <button type="submit" class="btn btn-primary rounded-pill ms-2">Search</button>
        <button type="button" class="btn btn-primary rounded-pill ms-2"onClick="window.location.href='{{ route('company.leave') }}' ">Reset </button>
    </form>
</div>
        <h4 class="mt-4">Leave Dashboard</h4> 

        <div class="table-responsive shadow-lg mt-4"> <!-- Added shadow-lg for a shadow effect -->
            <table class="table table-hover table-striped table-borderless align-middle w-100"> <!-- Full width with w-100 -->
                <thead class="text-black">
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
                        <td>{{ $leave->total_sick_leave }}</td>
                        <td>{{ $leave->sick_leave_taken }}</td>
                        <td>{{ $leave->total_sick_leave - $leave->sick_leave_taken }}</td>
                    </tr>
                    <tr>
                        <td>Annual Leave</td>
                        <td>{{ $leave->total_annual_leave }}</td>
                        <td>{{ $leave->annual_leave_taken }}</td>
                        <td>{{ $leave->total_annual_leave - $leave->annual_leave_taken }}</td>
                    </tr>
                    <tr>
                        <td>Public Holiday</td>
                        <td>{{ $leave->total_public_holiday }}</td>
                        <td>{{ $leave->public_holiday_taken }}</td>
                        <td>{{ $leave->total_public_holiday - $leave->public_holiday_taken }}</td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>

@endsection
