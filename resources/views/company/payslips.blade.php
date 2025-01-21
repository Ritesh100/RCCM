@extends('company.sidebar')
<link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
@section('content')
    <style>
        body{
            font-family: 'Josefin Sans', sans-serif;
        }
        a {
            text-decoration: none;
        }

        .search-box {
            margin-bottom: 20px;
        }

        .employee-section {
            margin-bottom: 30px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
        }

        .employee-header {
            background-color: #f8f9fa;
            padding: 10px 15px;
            border-bottom: 1px solid #dee2e6;
        }

        .employee-content {
            padding: 15px;
        }

        .no-data {
            padding: 20px;
            text-align: center;
            color: #6c757d;
        }
    </style>

        <div class="containe-fluid">
            <h1 class="mb-4 text-center">PaySlip</h1>

            <div class="d-flex justify-content-center mt-4 mb-4">
                <form action="{{ route('company.payslips') }}" method="GET"  class="input-group" style="max-width: 600px; margin: auto;">
                        <input type="text" name="search" class="form-control rounded-pill" placeholder="Search by User name" value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary rounded-pill ms-2">Search</button>
                        <button type="button" class="btn btn-secondary rounded-pill ms-2" 
                        onClick="window.location.href='{{ route('company.payslips') }}'">Reset</button>                    </div>
                </form>
            </div>

            <form action="{{ route('company.payslips') }}" method="GET" class="input-group" style="max-width: 1000px;"> 
            
                <select name="username" class="form-select me-2 filter-select mb-2">
                    <option value="">Select Username</option>
                    @foreach($uniqueUsernames as $username)
                        <option value="{{ $username }}" {{ request('username') == $username ? 'selected' : '' }}>{{ $username }}</option>
                    @endforeach
                </select>
            
                <select name="useremail" class="form-select me-2 filter-select mb-2">
                    <option value="">Select user email</option>
                    @foreach($uniqueUseremails as $useremail)
                        <option value="{{ $useremail }}" {{ request('useremail') == $useremail ? 'selected' : '' }}>{{ $useremail }}</option>
                    @endforeach
                </select>
            
                <button type="submit" class="btn btn-primary rounded-pill ms-2 mb-2">Filter</button>
                <button type="button" class="btn btn-secondary rounded-pill ms-2 mb-2" onClick="window.location.href='{{ route('company.payslips') }}'">Reset</button>
            </form>
            


        @if (empty($userPayslips))
            <div class="alert alert-warning">
                No payslip data available for any employees.
            </div>
        @else
            @forelse($userPayslips as $userData)
                <div class="employee-section">
                    <div class="employee-header">
                        <h4 class="m-0">{{ $userData['user']->name }}</h4>
                        <small class="text-muted">{{ $userData['user']->email }}</small>
                    </div>
                    <div class="employee-content">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Week Range</th>
                                    <th>Hours Worked</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($userData['dateRanges'] as $range)
                                    <tr>
                                        <td>{{ $range['start'] }} - {{ $range['end'] }}</td>
                                        <td>
                                            @if (isset($range['status']) && $range['status'] === 'pending')
                                                Pending
                                            @else
                                                {{ $range['hours'] }} hrs
                                            @endif
                                        </td>
                                        @php
                                        $endDate = \Carbon\Carbon::parse($range['end']);
                                        $currentDate = \Carbon\Carbon::now();
                                        @endphp
                                        <td class="text-end">
                                            @if (isset($range['status']) && $range['status'] === 'pending')
                                                Your timesheet is still pending, please verify timesheets.
                                            @elseif ($endDate <= $currentDate)
                                            <a href="{{ route('company.generatepayslip', [
                                                'userId' => $userData['user']->id,
                                                'weekRange' => $range['start'] . ' - ' . $range['end'],
                                            ]) }}"
                                                class="btn btn-sm btn-primary" target="_blank">
                                                <i class="fas fa-file-alt"></i> View Payslip
                                            </a>
                                            @else
                                                Your timesheet is still pending, please verify timesheets.
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            
                            </tbody>
                        </table>
                    </div>
                </div>
            @empty
                <div class="no-data">
                    <h3>No Results Found</h3>
                    <p>No employees match your search criteria.</p>
                </div>
            @endforelse
        @endif
    </div>
@endsection