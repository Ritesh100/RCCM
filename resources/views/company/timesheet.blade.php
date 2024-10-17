@extends('company.sidebar')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

@section('content')
<div class="container-fluid mt-4">
    <h3 class="mb-4">Timesheet Approval</h3>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>S.N.</th>
                            <th>Day</th>
                            <th>Cost Center</th>
                            <th>Date</th>
                            <th>Start Time</th>
                            <th>Close Time</th>
                            <th>Break Start</th>
                            <th>Break End</th>
                            <th>Timezone</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $index => $timesheet)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $timesheet->day }}</td>
                                <td>{{ $timesheet->cost_center }}</td>
                                <td>{{ $timesheet->date }}</td>
                                <td>{{ $timesheet->start_time }}</td>
                                <td>{{ $timesheet->close_time }}</td>
                                <td>{{ $timesheet->break_start }}</td>
                                <td>{{ $timesheet->break_end }}</td>
                                <td>{{ $timesheet->timezone }}</td>
                                <td>
                                    <span class="badge bg-{{ $timesheet->status == 'pending' ? 'warning' : 'success' }}">
                                        {{ ucfirst($timesheet->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if ($timesheet->status == 'pending')
                                        <form action="{{ route('timesheet.updateStatus', $timesheet->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-sm btn-primary" onclick="return confirm('Are you sure you want to approve this timesheet?');">
                                                Approve
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn btn-sm btn-secondary" disabled>Approved</button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $users->links('pagination::bootstrap-4') }}
    </div>
</div>

<style>
    .table th {
        background-color: #f8f9fa;
    }
    .pagination {
        display: flex;
        justify-content: center;
        list-style-type: none;
        padding: 0;
    }
    .pagination li {
        margin: 0 5px;
    }
    .pagination .page-item .page-link {
        display: block;
        padding: 0.5rem 0.75rem;
        border: 1px solid #007bff;
        color: #007bff;
        text-decoration: none;
        border-radius: 5px;
        transition: background-color 0.3s, color 0.3s;
    }
    .pagination .page-item .page-link:hover {
        background-color: #007bff;
        color: white;
    }
    .pagination .page-item.active .page-link {
        background-color: #007bff;
        color: white;
        border: 1px solid #007bff;
    }
    .pagination .page-item.disabled .page-link {
        color: #6c757d;
        border: 1px solid #6c757d;
        pointer-events: none;
    }
    @media (max-width: 768px) {
        .table-responsive {
            overflow-x: auto;
        }
    }
</style>
@endsection
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
