@extends('admin.sidebar')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

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

    .collapse-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
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

    @if (empty($userPayslips))
        <div class="alert alert-warning">
            No payslip data available for any employees.
        </div>
    @else
        @forelse($userPayslips as $userData)
            <div class="card shadow-sm">
                <div class="card-header">
                    <div>
                        <h4 class="mb-0">Payslip for {{ $userData['user']->name }}</h4>
                        <p class="mb-0">Email: {{ $userData['user']->email }}</p>
                    </div>
                  
                </div>
                
                <div id="payslipTable{{ $userData['user']->id }}" class="employee-section">
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
                                                    <button type="submit" class="btn btn-danger btn-sm mt-3">
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
