@extends('admin.sidebar')

@section('content')
<style>
    a {
        text-decoration: none;
    }

  
    .employee-section {
        margin-bottom: 30px;
        border: 1px solid #dee2e6;
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .employee-header {
        background-color: #f8f9fa;
        padding: 15px;
        border-bottom: 1px solid #dee2e6;
    }

    .company-info {
        color: #6c757d;
        margin-top: 5px;
    }

    .employee-content {
        padding: 15px;
    }

    .no-data {
        padding: 40px;
        text-align: center;
        color: #6c757d;
        background-color: #f8f9fa;
        border-radius: 5px;
    }

    .stats-container {
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
    }

    .stat-card {
        flex: 1;
        padding: 15px;
        background-color: #fff;
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        border: 1px solid #dee2e6;
    }

    .stat-card h5 {
        color: white;
        margin-bottom: 10px;
    }

    .stat-card .value {
        font-size: 1.5rem;
        font-weight: bold;
        color: black;
    }
    .stat-card:nth-child(1) {
    background-color: #007bff; /* Blue */
}

.stat-card:nth-child(2) {
    background-color: #28a745; /* Green */
}

.stat-card:nth-child(3) {
    background-color: #ffc107; /* Yellow */
}
</style>

<div class="container">
    <!-- Search and Filter Section -->
     <!-- Global Search form -->
  <div class="d-flex justify-content-center mt-4 mb-4">
    <form action="{{ route('admin.payslips') }}" method="GET" class="input-group" style="max-width: 600px;">
        <input type="text" name="search" class="form-control rounded-pill" 
            placeholder="Search by user name and user email" value="{{ $searchQuery }}">
        <button type="submit" class="btn btn-primary rounded-pill ms-2">Search</button>
        <button type="button" class="btn btn-secondary rounded-pill ms-2"  
                onClick="window.location.href='{{ route('admin.payslips') }}'">Reset</button>
    </form>
</div>

<form action="{{ route('admin.payslips') }}" method="GET" class="input-group" style="max-width: 1000px;">
    <select name="username" class="form-select me-2 filter-select mb-2">
        <option value="">Select User name</option>
        @foreach($uniqueUsernames as $username)
            <option value="{{ $username }}" {{ request('username') == $username ? 'selected' : '' }}>{{ $username }}</option>
        @endforeach
    </select>

    <select name="useremail" class="form-select me-2 filter-select mb-2">
        <option value="">Select User email</option>
        @foreach($uniqueUseremails as $useremail)
            <option value="{{ $useremail }}" {{ request('useremail') == $useremail ? 'selected' : '' }}>{{ $useremail }}</option>
        @endforeach
    </select>

    <button type="submit" class="btn btn-primary rounded-pill ms-2 mb-2">Filter</button>
    <button type="button" class="btn btn-secondary rounded-pill ms-2 mb-2" onClick="window.location.href='{{ route('admin.payslips') }}'">Reset</button>
</form>

    @if (empty($userPayslips))
        <div class="alert alert-warning">
            No payslip data available for any employees.
        </div>
    @else
        <!-- Statistics Summary -->
        {{-- <div class="stats-container">
            <div class="stat-card">
                <h5>Total Companies</h5>
                <div class="value">{{ $companies->count() }}</div>
            </div>
            <div class="stat-card">
                <h5>Total Users</h5>
                <div class="value">{{ count($userPayslips) }}</div>
            </div>
            
            <div class="stat-card">
                <h5>PaySlip</h5>
                <div class="value">
                    {{ collect($userPayslips)->sum(function($userData) {
                        return count($userData['dateRanges']);
                    }) }}
                </div>
            </div>
        </div> --}}

        @forelse($userPayslips as $userData)
            <div class="employee-section">
                <div class="employee-header">
                    <h4 class="m-0">{{ $userData['user']->name }}</h4>
                    <div class="company-info">
                        <i class="fas fa-envelope"></i> {{ $userData['user']->email }}
                       
                    </div>
                </div>
                <div class="employee-content">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Week Range</th>
                                <th>Hours Worked</th>
                                <th>Rate ({{ $userData['user']->currency ?? 'NPR' }})</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($userData['dateRanges'] as $range)
                                <tr>
                                    <td>{{ $range['start'] }} - {{ $range['end'] }}</td>
                                    <td>{{ $range['hours'] }} hrs</td>
                                    <td>{{ number_format($userData['user']->hrlyRate, 2) }}</td>
                                    @php
                                    $endDate = \Carbon\Carbon::parse($range['end']);
                                    $currentDate = \Carbon\Carbon::now();
                                    @endphp
                                    <td class="text-end">
                                        @if ($endDate <= $currentDate)
                                        <a href="{{ route('admin.generatepayslip', [
                                            'userId' => $userData['user']->id,
                                            'weekRange' => $range['start'] . ' - ' . $range['end'],
                                        ]) }}"
                                            class="btn btn-sm btn-primary" 
                                            target="_blank">
                                            <i class="fas fa-file-alt"></i> View Payslip
                                        </a>
                                        @else Pending
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