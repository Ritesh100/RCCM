@extends('company.sidebar')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>


    body{
        font-family: 'Josefin Sans', sans-serif;
    }

        .custom-header {
            background: linear-gradient(to right, #6c757d, #adb5bd);
            color: white;
        }
    
        .custom-header th {
            padding: 5px;
            text-align: center;
        }
        .custom-btn-white {
        background-color: white !important; 
        color: #5271ff !important; 
        border: 2px solid #5271ff !important; 
        font-weight: 600; 
        padding: 0.5rem 1.5rem;
        
        transition: all 0.3s ease; 
    }

    .custom-btn-white:hover {
        background-color: #5271ff !important;  
        color: white !important; 
    }
        
    </style>


<div class="container-fluid">
    <h1 class="mb-4 text-left" style="color: #575b5b;">Leave Dashboard</h1>

    <form method="GET" action="{{ route('company.leave') }}" class="input-group" style="max-width: 1000px;">
            <select name="searchName" class="form-select me-2 filter-select mb-2">
                <option value="">Select Name</option>
                @foreach($users as $user)
                    <option value="{{ $user->name }}" {{ request('searchName') == $user->name ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="btn custom-btn-white rounded-pill ms-2 mb-2">Filter</button>
            <button type="button" class="btn custom-btn-white rounded-pill ms-2 mb-2" onClick="window.location.href='{{ route('company.leave') }}'">Reset</button>
    </form>        


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
                        <td>Sick Leave(10 days)</td>
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
                        <td>Public Holiday(13 days)</td>
                        <td>{{ $leave->total_public_holiday }}</td>
                        <td>{{ $leave->public_holiday_taken }}</td>
                        <td>{{ $leave->total_public_holiday - $leave->public_holiday_taken }}</td>
                    </tr>
                    <tr>
                        <td>Unpaid Leave (0 day)</td>
                        <td>0</td>
                        <td>{{ $leave->taken_unpaid_leave }}</td>
                        <td>0</td>
                    </tr>
                    <tr><td colspan="5" style="height: 10px;"></td></tr>

                    
                @endforeach


            </tbody>
  

        </table>
    </div>

@endsection
