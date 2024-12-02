{{-- @extends('admin.sidebar')

@section('content')
<style>
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

    .employee-content {
        padding: 15px;
    }

    .table-responsive {
        border-radius: 5px;
        overflow: hidden;
    }

    .table th, .table td {
        vertical-align: middle;
    }

    .btn {
        border-radius: 30px;
        font-size: 0.9rem;
    }

    .btn-sm {
        padding: 5px 10px;
    }

    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 20px;
    }

    .stat-card {
        padding: 15px;
        background-color: #fff;
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        border: 1px solid #dee2e6;
    }

    .stat-card h5 {
        color: #fff;
        margin-bottom: 10px;
    }

    .stat-card .value {
        font-size: 1.5rem;
        font-weight: bold;
        color: white;
    }

    .stat-card:nth-child(1) {
        background-color: #007bff;
    }

    .stat-card:nth-child(2) {
        background-color: #28a745;
    }

    .stat-card:nth-child(3) {
        background-color: #ffc107;
    }

    .no-data {
        padding: 40px;
        text-align: center;
        color: #6c757d;
        background-color: #f8f9fa;
        border-radius: 5px;
    }

    .search-form .form-control, .search-form .btn {
        border-radius: 30px;
    }
</style>

<div class="container">
    <!-- Search and Filter Section -->
    <div class="d-flex justify-content-center mt-4 mb-4">
        <form action="{{ route('admin.payslips') }}" method="GET" class="input-group search-form" style="max-width: 600px;">
            <input type="text" name="search" class="form-control" placeholder="Search by user name or email" value="{{ $searchQuery }}">
            <button type="submit" class="btn btn-primary ms-2">Search</button>
            <button type="button" class="btn btn-secondary ms-2" onclick="window.location.href='{{ route('admin.payslips') }}'">Reset</button>
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
        @forelse($userPayslips as $userData)
        <div class="employee-section">
            <div class="employee-content">
                <div class="table-responsive">
                    <table class="table table-striped">
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
                                <td>
                                    @if ($range['status'] === 'pending')
                                        Pending
                                    @else
                                        {{ $range['hours'] }} hrs
                                    @endif
                                </td>
                                <td>{{ number_format($userData['user']->hrlyRate, 2) }}</td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end align-items-center">
                                        <a href="{{route('admin.editPayslip',  ['userId' => $userData['user']->id, 'weekRange' => $range['start'] . ' - ' . $range['end']]) }}" class="btn btn-success btn-sm me-2">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="{{ route('admin.generatepayslip', ['userId' => $userData['user']->id, 'weekRange' => $range['start'] . ' - ' . $range['end']]) }}" class="btn btn-primary btn-sm me-2" target="_blank">
                                            <i class="fas fa-file-alt"></i> View
                                        </a>
                                        <form action="{{ route('admin.payslips') }}" method="GET" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this payslip and associated timesheets?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="userId" value="{{ $userData['user']->id }}">
                                            <input type="hidden" name="weekRange" value="{{ $range['start'] . ' - ' . $range['end'] }}">
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
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
@endsection --}}
