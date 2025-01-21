@extends('admin.sidebar')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

@section('content')
<style>
    body{
        font-family: 'Josefin Sans', sans-serif;
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
    <h1 class="mb-4 text-center">Payslip Management</h1>

    <form method="GET" action="{{ route('admin.payslips') }}" class="input-group" style="max-width: 1000px;">
        <select name="username" class="form-select me-2 filter-select mb-2">
            <option value="">Select Username</option>
            @foreach($uniqueUsernames as $username)
                <option value="{{ $username }}" {{ request('username') == $username ? 'selected' : '' }}>
                    {{ $username }}
                </option>
            @endforeach
        </select>
  
        <select name="useremail" class="form-select me-2 filter-select mb-2">
            <option value="">Select User Email</option>
            @foreach($uniqueUseremails as $email)
                <option value="{{ $email }}" {{ request('useremail') == $email ? 'selected' : '' }}>
                    {{ $email }}
                </option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-primary rounded-pill ms-2 mb-2">Filter</button>
        <button type="button" class="btn btn-secondary rounded-pill ms-2 mb-2" onClick="window.location.href='{{ route('admin.payslips') }}'">Reset</button>
    </form>

    @php
        $isFiltered = request()->has('username') || request()->has('useremail');
    @endphp

    @if (!$isFiltered)
    <div class="alert alert-info mt-4">
        Please select a user name or user email to view the leave records.
    </div>
    @else
        @if (empty($userPayslips))
            <div class="alert alert-warning mt-4">
                No payslip data available for the selected filters.
            </div>
        @else
            @forelse($userPayslips as $userData)
                <div class="employee-section mt-4">
                    <div class="employee-content">
                        <div class="employee-header">
                            <h4 class="m-2">{{ $userData['user']->name }}</h4>
                            <small class="text-muted m-2">{{ $userData['user']->email }}</small>
                        </div>
                        <hr>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Week Range</th>
                                        <th>Hours Worked</th>
                                        <th>Rate ({{ $userData['user']->currency ?? 'NPR' }})</th>
                                        <th>Hide/Show</th>
                                        <th>Status</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($userData['dateRanges'] as $range)
                                        @php
                                            $payslip = \App\Models\Payslip::where('user_id', $userData['user']->id)
                                                ->where('week_range', $range['start'] . ' - ' . $range['end'])
                                                ->first();
                                        @endphp
                                        <tr class="{{ $payslip && $payslip->status === 'deleted' ? 'text-muted' : '' }}">
                                            <td>{{ $range['start'] }} - {{ $range['end'] }}</td>
                                            <td>
                                                @if ($range['status'] === 'pending')
                                                    Pending
                                                @else
                                                    {{ $range['hours'] }} hrs
                                                @endif
                                            </td>
                                            <td>{{ number_format($userData['user']->hrlyRate, 2) }}</td>
                                            <td>
                                                <form action="{{ route('admin.togglePayslipStatus') }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="userId" value="{{ $userData['user']->id }}">
                                                    <input type="hidden" name="weekRange" value="{{ $range['start'] . ' - ' . $range['end'] }}">
                                                    <div class="form-check form-switch">
                                                        <input 
                                                            class="form-check-input" 
                                                            type="checkbox" 
                                                            role="switch" 
                                                            id="payslipToggle-{{ $userData['user']->id }}-{{ $loop->index }}"
                                                            name="status"
                                                            onchange="this.form.submit()"
                                                            {{ $payslip && $payslip->disable == 0 ? 'checked' : '' }}
                                                        >
                                                    </div>
                                                </form>
                                            </td>
                                            <td>
                                                {{ $payslip ? ucfirst($payslip->status) : 'N/A' }}
                                            </td>
                                            <td class="text-end">
                                                <div class="d-flex justify-content-end align-items-center">
                                                    @if($payslip && $payslip->status !== 'deleted')
                                                        <a href="{{ route('admin.editPayslip', ['userId' => $userData['user']->id, 'weekRange' => $range['start'] . ' - ' . $range['end']]) }}" class="btn btn-success btn-sm me-2">
                                                            <i class="fas fa-edit"></i> Edit
                                                        </a>
                                                        <a href="{{ route('admin.generatepayslip', ['userId' => $userData['user']->id, 'weekRange' => $range['start'] . ' - ' . $range['end']]) }}" class="btn btn-primary btn-sm me-2" target="_blank">
                                                            <i class="fas fa-file-alt"></i> View
                                                        </a>
                                                        <form action="{{ route('admin.deletePayslip') }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this payslip?');">
                                                            @csrf
                                                            <input type="hidden" name="userId" value="{{ $userData['user']->id }}">
                                                            <input type="hidden" name="weekRange" value="{{ $range['start'] . ' - ' . $range['end'] }}">
                                                            <button type="submit" class="btn btn-danger btn-sm">
                                                                <i class="fas fa-trash"></i> Delete
                                                            </button>
                                                        </form>
                                                    @elseif($payslip && $payslip->status === 'deleted')
                                                        <form action="{{ route('admin.restorePayslip') }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to restore this payslip?');">
                                                            @csrf
                                                            <input type="hidden" name="userId" value="{{ $userData['user']->id }}">
                                                            <input type="hidden" name="weekRange" value="{{ $range['start'] . ' - ' . $range['end'] }}">
                                                            <button type="submit" class="btn btn-success btn-sm">
                                                                <i class="fas fa-undo"></i> Restore
                                                            </button>
                                                        </form>
                                                    @endif
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
            <div class="alert alert-info mt-4">
                Please select a user name or user email to view the leave records.
            </div>
            @endforelse
        @endif
    @endif
</div>

<script>
function disablePayslip(id) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch(`/payslips/${id}/toggle-disable`, { 
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({})
    })
    .then(response => {
        if (response.ok) {
            alert('Payslip status updated successfully.');
            location.reload();
        } else {
            alert('Failed to update payslip status.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    });
}
</script>
@endsection