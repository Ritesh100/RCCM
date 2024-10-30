@extends('admin.sidebar')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <style>
        .company-section {
            margin-bottom: 30px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .company-header {
            background-color: #f8f9fa;
            padding: 15px;
            border-bottom: 1px solid #dee2e6;
        }

        .company-info {
            color: #6c757d;
            margin-top: 5px;
        }
    </style>

    <div class="container">
        <h3>Admin Timesheet Management</h3>

        <!-- Global Search form -->
        <div class="d-flex justify-content-center mt-4 mb-4">
            <form action="{{ route('admin.timesheets') }}" method="GET" class="input-group" style="max-width: 600px;">
                <input type="text" name="search" class="form-control rounded-pill" 
                    placeholder="Search by company name" value="{{ $searchQuery }}">
                <button type="submit" class="btn btn-primary rounded-pill ms-2">Search</button>
                <button type="button" class="btn btn-secondary rounded-pill ms-2"  
                        onClick="window.location.href='{{ route('admin.timesheets') }}'">Reset</button>
            </form>
        </div>
        <div class="export-buttons text-end justify-content-end g-1 text-nowrap mb-1" >
            <a href="{{ route('export.timesheets.all') }}" 
                class="btn btn-success btn-sm ">
                <i class="fas fa-file-excel me-2"></i>Export All
            </a>
            <a href="{{ route('export.timesheets.approved') }}" 
                class="btn btn-primary btn-sm">
                <i class="fas fa-check-circle me-2"></i>Export Approved
            </a>
            <a href="{{ route('export.timesheets.pending') }}" 
                class="btn btn-warning btn-sm">
                <i class="fas fa-clock me-2"></i>Export Pending
            </a>
        </div>
       

        @foreach($timesheets->groupBy('company_email') as $companyEmail => $companyTimesheets)
            <div class="company-section">
                <div class="company-header">
                    <h4>{{ $companyTimesheets->first()->company_name ?? 'Unknown Company' }}</h4>
                    <div class="company-info">
                        <i class="fas fa-envelope"></i> {{ $companyEmail }}
                        

                    </div>
                </div>

                <!-- Pending Timesheets -->
                <h5 class="p-2 mt-2">Pending Timesheets</h5>
                <div class="table-responsive shadow-lg mt-2">
                    <table class="table table-striped table-hover table-bordered align-middle w-100">
                        <thead class="text-black">
                            <tr class="text-nowrap">
                                <th>S.N.</th>
                                <th>User Name</th>
                                <th>Day</th>
                                <th>Email</th>
                                <th>Cost Center</th>
                                <th>Currency</th>
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
                            @foreach ($companyTimesheets->where('status', 'pending') as $index => $timesheet)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $timesheet->user_name }}</td>
                                    <td>{{ $timesheet->day }}</td>
                                    <td>{{ $timesheet->user_email }}</td>
                                    <td>{{ $timesheet->cost_center }}</td>
                                    <td>{{ $timesheet->currency }}</td>
                                    <td>{{ $timesheet->date }}</td>
                                    <td>{{ $timesheet->start_time }}</td>
                                    <td>{{ $timesheet->close_time }}</td>
                                    <td>{{ $timesheet->break_start }}</td>
                                    <td>{{ $timesheet->break_end }}</td>
                                    <td>{{ $timesheet->timezone }}</td>
                                    <td>
                                        <span class="badge 
                                            {{ $timesheet->status == 'approved' ? 'bg-success' : '' }}
                                            {{ $timesheet->status == 'pending' ? 'bg-warning text-dark' : '' }}
                                            {{ $timesheet->status == 'deleted' ? 'bg-danger' : '' }}">
                                            {{ ucfirst($timesheet->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $timesheet->work_time }}</td>
                                    <td class="text-nowrap">
                                        <form action="{{ route('admin.timesheet.updateStatus', $timesheet->id) }}" method="POST" class="d-inline">
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

                <!-- Approved Timesheets -->
                <h5 class="p-2 mt-2">Approved Timesheets</h5>
                <div class="table-responsive shadow-lg mt-2">
                    <table class="table table-striped table-hover table-bordered align-middle w-100">
                        <thead class="text-black">
                            <tr class="text-nowrap">
                                <th>S.N.</th>
                                <th>User Name</th>
                                <th>Day</th>
                                <th>Email</th>
                                <th>Cost Center</th>
                                <th>Currency</th>
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
                            @foreach ($companyTimesheets->where('status', 'approved') as $index => $timesheet)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $timesheet->user_name }}</td>
                                    <td>{{ $timesheet->day }}</td>
                                    <td>{{ $timesheet->user_email }}</td>
                                    <td>{{ $timesheet->cost_center }}</td>
                                    <td>{{ $timesheet->currency }}</td>
                                    <td>{{ $timesheet->date }}</td>
                                    <td>{{ $timesheet->start_time }}</td>
                                    <td>{{ $timesheet->close_time }}</td>
                                    <td>{{ $timesheet->break_start }}</td>
                                    <td>{{ $timesheet->break_end }}</td>
                                    <td>{{ $timesheet->timezone }}</td>
                                    <td>
                                        <span class="badge 
                                            {{ $timesheet->status == 'approved' ? 'bg-success' : '' }}
                                            {{ $timesheet->status == 'pending' ? 'bg-warning text-dark' : '' }}
                                            {{ $timesheet->status == 'deleted' ? 'bg-danger' : '' }}">
                                            {{ ucfirst($timesheet->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $timesheet->work_time }}</td>
                                    <td class="text-nowrap">
                                        <form action="{{ route('admin.timesheet.updateStatus', $timesheet->id) }}" method="POST" class="d-inline">
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
            </div>
        @endforeach

        <div class="pagination">
            {{ $timesheets->links('pagination::bootstrap-4') }}
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
                            <div class="mb-3">
                                <label for="editEmail" class="form-label">User Email</label>
                                <input type="email" class="form-control" id="editEmail" name="user_email" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="editCostCenter" class="form-label">Cost Center</label>
                                <select name="cost_center" class="form-control" id="editCostCenter">
                                    <option value="hrs_worked">Hrs Worked</option>
                                    <option value="annual_leave">Annual Leave</option>
                                    <option value="sick_leave">Sick Leave</option>
                                    <option value="public_holiday">Public Holiday</option>
                                    <option value="unpaid_leave">Other Unpaid Leave</option>
                                    <option value="paid_leave">Other Paid Leave</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="editCurrency" class="form-label">Currency</label>
                                <select name="currency" class="form-control" id="editCurrency">
                                    <option value="NPR">Nepali Rupee (NPR)</option>
                                    <option value="USD">United States Dollar (USD)</option>
                                    <option value="EUR">Euro (EUR)</option>
                                    <option value="JPY">Japanese Yen (JPY)</option>
                                    <option value="GBP">British Pound Sterling (GBP)</option>
                                    <option value="AUD">Australian Dollar (AUD)</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="editDate" class="form-label">Date</label>
                                <input type="date" class="form-control" id="editDate" name="date">
                            </div>
                            <div class="mb-3">
                                <label for="editStartTime" class="form-label">Start Time</label>
                                <input type="time" class="form-control" id="editStartTime" name="start_time"
                                    oninput="calculateWorkTime()">
                            </div>
                            <div class="mb-3">
                                <label for="editCloseTime" class="form-label">Close Time</label>
                                <input type="time" class="form-control" id="editCloseTime" name="close_time"
                                    oninput="calculateWorkTime()">
                            </div>
                            <div class="mb-3">
                                <label for="editBreakStart" class="form-label">Break Start</label>
                                <input type="time" class="form-control" id="editBreakStart" name="break_start"
                                    oninput="calculateWorkTime()">
                            </div>
                            <div class="mb-3">
                                <label for="editBreakEnd" class="form-label">Break End</label>
                                <input type="time" class="form-control" id="editBreakEnd" name="break_end"
                                    oninput="calculateWorkTime()">
                            </div>
                            <div class="mb-3">
                                <label for="WorkTime" class="form-label">Work Time</label>
                                <input type="text" class="form-control" id="WorkTime" name="work_time" readonly>
                            </div>

                            <div class="mb-3">
                                <label for="editTimezone" class="form-label">Timezone</label>
                                <select name="timezone" id="editTimezone" class="form-control">
                                    <option value="Australia/Sydney">Australia/Sydney (AEST)</option>
                                    <option value="Australia/Melbourne">Australia/Melbourne (AEST)</option>
                                    <option value="Australia/Brisbane">Australia/Brisbane (AEST)</option>
                                    <option value="Australia/Perth">Australia/Perth (AWST)</option>
                                    <option value="Australia/Adelaide">Australia/Adelaide (ACST)</option>
                                    <option value="Australia/Darwin">Australia/Darwin (ACST)</option>
                                    <option value="Australia/Hobart">Australia/Hobart (AEST)</option>
                                    <option value="Australia/Broken_Hill">Australia/Broken Hill (ACST)</option>
                                    <option value="Australia/Lord_Howe">Australia/Lord Howe (LHST)</option>
                                </select>
                            </div>



                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
         function openEditModal(timesheet) {
                // Populate the modal fields with the selected timesheet's data
                document.getElementById('timesheet_id').value = timesheet.id;
                document.getElementById('editDay').value = timesheet.day;
                document.getElementById('editEmail').value = timesheet.user_email;
                document.getElementById('editCostCenter').value = timesheet.cost_center;
                document.getElementById('editCurrency').value = timesheet.currency;
                document.getElementById('editDate').value = timesheet.date;
                document.getElementById('editStartTime').value = timesheet.start_time;
                document.getElementById('editCloseTime').value = timesheet.close_time;
                document.getElementById('editBreakStart').value = timesheet.break_start;
                document.getElementById('editBreakEnd').value = timesheet.break_end;

                // Set the timezone value
                document.getElementById('editTimezone').value = timesheet.timezone;

                // Set the calculated work time
                document.getElementById('WorkTime').value = timesheet.work_time; // Ensure this matches your field ID

                // Set the form action to the correct route
                document.getElementById('editTimesheetForm').action = `/timesheet/update/${timesheet.id}`;
            }

        function calculateWorkTime(startTime, closeTime, breakStart, breakEnd) {
            const start = new Date(`1970-01-01T${startTime}:00`);
            const close = new Date(`1970-01-01T${closeTime}:00`);
            const breakStartTime = breakStart ? new Date(`1970-01-01T${breakStart}:00`) : null;
            const breakEndTime = breakEnd ? new Date(`1970-01-01T${breakEnd}:00`) : null;

            let workTime = (close - start) / (1000 * 60 * 60); // hours
            if (breakStartTime && breakEndTime) {
                workTime -= (breakEndTime - breakStartTime) / (1000 * 60 * 60); // deduct break time
            }

            document.getElementById('work_time').value = workTime.toFixed(2) + ' hours';
        }

        document.getElementById('start_time').addEventListener('change', function () {
            const startTime = this.value;
            const closeTime = document.getElementById('close_time').value;
            const breakStart = document.getElementById('break_start').value;
            const breakEnd = document.getElementById('break_end').value;

            calculateWorkTime(startTime, closeTime, breakStart, breakEnd);
        });

        document.getElementById('close_time').addEventListener('change', function () {
            const closeTime = this.value;
            const startTime = document.getElementById('start_time').value;
            const breakStart = document.getElementById('break_start').value;
            const breakEnd = document.getElementById('break_end').value;

            calculateWorkTime(startTime, closeTime, breakStart, breakEnd);
        });

        document.getElementById('break_start').addEventListener('change', function () {
            const breakStart = this.value;
            const startTime = document.getElementById('start_time').value;
            const closeTime = document.getElementById('close_time').value;
            const breakEnd = document.getElementById('break_end').value;

            calculateWorkTime(startTime, closeTime, breakStart, breakEnd);
        });

        document.getElementById('break_end').addEventListener('change', function () {
            const breakEnd = this.value;
            const startTime = document.getElementById('start_time').value;
            const closeTime = document.getElementById('close_time').value;
            const breakStart = document.getElementById('break_start').value;

            calculateWorkTime(startTime, closeTime, breakStart, breakEnd);
        });
    </script>
@endsection
