@extends('company.sidebar')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <style>
        .custom-header {
            background: linear-gradient(to right, #6c757d, #adb5bd);
            color: white;
        }
    
        .custom-header th {
            padding: 12px 15px;
            text-align: center;
        }
    </style>

<div class="container">
    <h3>Timesheet Management</h3>

    <!-- Search form -->
    <div class="d-flex justify-content-center mt-4 mb-4">

        <form action="{{ route('company.timeSheet') }}" method="GET" class="input-group" style="max-width: 600px;">
            <input type="text" name="search" class="form-control rounded-pill" placeholder="Search by name" value="{{ $searchQuery }}">
            <button type="submit" class="btn btn-primary rounded-pill ms-2">Search</button>
        </form>
    </div>
    
    

    <h5>Pending Timesheets</h5>
    <div class="table-responsive mt-4">
        <table class="table table-striped table-hover table-bordered">
            <thead class="custom-header">
                <tr class="text-nowrap">
                    <th>S.N.</th>
                    <th>Day</th>
                    <th>Email</th>
                    <th>Cost Center</th>
                    <th>Date</th>
                    <th>Start Time</th>
                    <th>Close Time</th>
                    <th>Break Start</th>
                    <th>Break End</th>
                    <th>Timezone</th>
                    <th>Status</th>
                    <th>Work Time</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users->where('status', 'pending') as $index => $timesheet)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $timesheet->day }}</td>
                    <td>{{ $timesheet->name }}</td>
                    <td>{{ $timesheet->cost_center }}</td>
                    <td>{{ $timesheet->date }}</td>
                    <td>{{ $timesheet->start_time }}</td>
                    <td>{{ $timesheet->close_time }}</td>
                    <td>{{ $timesheet->break_start }}</td>
                    <td>{{ $timesheet->break_end }}</td>
                    <td>{{ $timesheet->timezone }}</td>
                    <td >
                        <span class="badge 
                            {{ $timesheet->status == 'approved' ? 'bg-success' : '' }}
                            {{ $timesheet->status == 'pending' ? 'bg-warning text-dark' : '' }}
                            {{ $timesheet->status == 'deleted' ? 'bg-danger' : '' }}">
                            {{ ucfirst($timesheet->status) }}
                        </span>
                    </td>
                    
                    <td>{{ $timesheet->work_time }}</td>
                    <td class="text-nowrap">
                        <form action="{{ route('timesheet.updateStatus', $timesheet->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PUT')
                            <select name="status" class="form-select form-select-sm mb-2">
                                <option value="pending" {{ $timesheet->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ $timesheet->status == 'approved' ? 'selected' : '' }}>Approve</option>
                                <option value="deleted">Delete</option>
                            </select>
                            <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Are you sure?');">
                                Update
                            </button>
                        </form>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal" onclick="openEditModal({{ json_encode($timesheet) }})">
                            Edit
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    

    <!-- Approved Timesheets -->
    <h5>Approved Timesheets</h5>
    <div class="table-responsive mt-4">
        <table class="table table-striped table-hover table-bordered">
            <thead class="custom-header">
                <tr class="text-nowrap">
                    <th>S.N.</th>
                    <th>Day</th>
                    <th>Email</th>
                    <th>Cost Center</th>
                    <th>Date</th>
                    <th>Start Time</th>
                    <th>Close Time</th>
                    <th>Break Start</th>
                    <th>Break End</th>
                    <th>Timezone</th>
                    <th>Status</th>
                    <th>Work Time</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users->where('status', 'approved') as $index => $timesheet)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $timesheet->day }}</td>
                    <td>{{ $timesheet->name }}</td>
                    <td>{{ $timesheet->cost_center }}</td>
                    <td>{{ $timesheet->date }}</td>
                    <td>{{ $timesheet->start_time }}</td>
                    <td>{{ $timesheet->close_time }}</td>
                    <td>{{ $timesheet->break_start }}</td>
                    <td>{{ $timesheet->break_end }}</td>
                    <td>{{ $timesheet->timezone }}</td>
                    <td >
                        <span class="badge 
                            {{ $timesheet->status == 'approved' ? 'bg-success' : '' }}
                            {{ $timesheet->status == 'pending' ? 'bg-warning text-dark' : '' }}
                            {{ $timesheet->status == 'deleted' ? 'bg-danger' : '' }}">
                            {{ ucfirst($timesheet->status) }}
                        </span>
                    </td>
                                        <td>{{ $timesheet->work_time }}</td>
                    <td class="text-nowrap">
                        <form action="{{ route('timesheet.updateStatus', $timesheet->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PUT')
                            <select name="status" class="form-select form-select-sm mb-2">
                                <option value="pending" {{ $timesheet->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ $timesheet->status == 'approved' ? 'selected' : '' }}>Approve</option>
                                <option value="deleted">Delete</option>
                            </select>
                            <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Are you sure?');">
                                Update
                            </button>
                        </form>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#editModal" onclick="openEditModal({{ json_encode($timesheet) }})">
                            Edit
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="pagination">
        {{ $users->links('pagination::bootstrap-4') }}
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Timesheet</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editTimesheetForm" action="" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="timesheet_id" id="timesheet_id">
                        <div class="mb-3">
                            <label for="editDay" class="form-label">Day</label>
                            <input type="text" class="form-control" id="editDay" name="day">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection