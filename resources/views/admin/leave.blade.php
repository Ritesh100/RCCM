@extends('admin.sidebar')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body{
            font-family: 'Josefin Sans', sans-serif;
        }
        .custom-header {
            background: linear-gradient(to right, #343a40, #495057);
            color: white;
        }

        .custom-header th {
            padding: 5px;
            text-align: center;
        }
        a {
        text-decoration: none;
    }

    .employee-section {
        margin-bottom: 30px;
        border: 1px solid #dee2e6;
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }
    </style>

    <div class="container-fluid">
        <h1 class="mb-4 text-center">Leave Management</h1>

        <!-- Filter Form -->
        <form method="GET" action="{{ route('admin.leave') }}" class="input-group" style="max-width: 1000px;">
            <select name="searchCompany" class="form-select me-2 filter-select mb-2">
                <option value="">Select Company</option>
                @foreach($companies as $company)
                    <option value="{{ $company->id }}" {{ request('searchCompany') == $company->id ? 'selected' : '' }}>
                        {{ $company->name }}
                    </option>
                @endforeach
            </select>
            <select name="searchUsername" class="form-select me-2 filter-select mb-2">
                <option value="">Select User</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ request('searchUsername') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        
          
            <button type="submit" class="btn btn-primary rounded-pill ms-2 mb-2">Filter</button>
            <button type="button" class="btn btn-secondary rounded-pill ms-2 mb-2" onClick="window.location.href='{{ route('admin.leave') }}'">Reset</button>
        </form>
        

        @if(request('searchCompany') || request('searchUsername'))
            @if($groupedLeaves->isEmpty())
                <div class="alert alert-info mt-4">
                    No leaves found for the selected filters.
                </div>
            @else
                @foreach ($groupedLeaves as $companyData)
               

                    <div class="employee-section">
                        <div class="employee-content">
                            <div class="employee-header">
                                <h4 class="m-2">{{ $companyData['company']->name }}</h4>
                                <small class="text-muted m-1">{{ $companyData['company']->email }}</small>
                                <hr>
                            </div>

                    <!-- Leave Table -->
                    <div class="table-responsive shadow-lg">
                        <table class="table table-hover table-striped table-borderless align-middle w-100">
                            <thead class="text-black">
                                <tr>
                                    <th>Name</th>
                                    <th>Leave Type</th>
                                    <th>Total Leaves (Hr)</th>
                                    <th>Leaves Taken (Hr)</th>
                                    <th>Remaining Leaves (Hr)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($companyData['leaves'] as $leave)
                                    <tr>
                                        <td rowspan="4">{{ $leave->rcUser->name }}</td>
                                        <td>Sick Leave(10 days)</td>
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
                        </div>
                    </div>
                </div>
                @endforeach
            @endif
        @else
            <div class="alert alert-info mt-4">
                Please select a company name or user name to view the leave records.
            </div>
        @endif
    </div>
@endsection