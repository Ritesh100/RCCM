@extends('company.sidebar')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <style>
      

    </style>

<div class="containe-fluid">
    <h1 class="mb-4 text-center">Timesheet Management</h1>


        <!-- Search form -->
        {{-- <div class="d-flex justify-content-center mt-4 mb-4">

            <form action="{{ route('company.timeSheet') }}" method="GET" class="input-group" style="max-width: 600px;">
                <input type="text" name="search" class="form-control rounded-pill" placeholder="Search by name"
                    value="{{ $searchQuery }}">
                <button type="submit" class="btn btn-primary rounded-pill ms-2">Search</button>
                <button type="button" class="btn btn-primary rounded-pill ms-2"  onClick="window.location.href='{{ route('company.timeSheet') }}'">Reset</button>

            </form>
        </div> --}}

            <form action="{{ route('company.timeSheet') }}" method="GET" class="input-group" style="max-width: 1000px;">
                <select name="username" class="form-select me-2 filter-select mb-2">
                    <option value="">Select Username</option>
                    @foreach($uniqueUsernames as $username)
                        <option value="{{ $username }}" {{ request('username') == $username ? 'selected' : '' }}>{{ $username }}</option>
                    @endforeach
                </select>
                
                <select name="day" class="form-select me-2 filter-select mb-2">
                    <option value="">Select Day</option>
                    @foreach($uniqueDays as $day)
                        <option value="{{ $day }}" {{ request('day') == $day ? 'selected' : '' }}>{{ $day }}</option>
                    @endforeach
                </select>
                
                <select name="cost_center" class="form-select me-2 filter-select mb-2">
                    <option value="">Select Cost Center</option>
                    @foreach($uniqueCostCenters as $costCenter)
                        <option value="{{ $costCenter }}" {{ request('cost_center') == $costCenter ? 'selected' : '' }}>{{ $costCenter }}</option>
                    @endforeach
                </select>
                
                <select name="status" class="form-select me-2 filter-select mb-2">
                    <option value="">Select Status</option>
                    @foreach($uniqueStatuses as $status)
                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
                
                <select name="date" class="form-select me-2 filter-select mb-2">
                    <option value="">Select Date</option>
                    @foreach($uniqueDates as $date)
                        <option value="{{ $date }}" {{ request('date') == $date ? 'selected' : '' }}>{{ $date }}</option>
                    @endforeach
                </select>
                
        
                <button type="submit" class="btn btn-primary rounded-pill ms-2 mb-2">Filter</button>
                <button type="button" class="btn btn-secondary rounded-pill ms-2 mb-2" onClick="window.location.href='{{ route('company.timeSheet') }}'">Reset</button>
            </form>
        

        <div class="export-buttons text-end justify-content-end g-1 text-nowrap mb-1" >
            <a href="{{ route('company.timesheet.export.all') }}" 
                    class="btn btn-success btn-sm ">
                    <i class="fas fa-file-excel me-2"></i>Export All
                </a>
                <a href="{{ route('company.timesheet.export.approved') }}" 
                    class="btn btn-primary btn-sm">
                    <i class="fas fa-check-circle me-2"></i>Export Approved
                </a>
                <a href="{{ route('company.timesheet.export.pending') }}" 
                    class="btn btn-warning btn-sm">
                    <i class="fas fa-clock me-2"></i>Export Pending
                </a>
            </div>

        <h5>Pending Timesheets</h5>
        <div class="table-responsive shadow-lg mt-4">
            <table class="table table-striped table-hover table-bordered  align-middle w-100 ">
                <thead class="text-black">
                    <tr class="text-nowrap">
                        <th>S.N.</th>
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
                    @foreach ($pendingTimesheets as $index => $timesheet)
                    <tr>
                        <td>{{ $pendingTimesheets->firstItem() + $index }}</td> <!-- This will display the correct number across paginated pages -->
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
                                <span
                                    class="badge 
                            {{ $timesheet->status == 'approved' ? 'bg-success' : '' }}
                            {{ $timesheet->status == 'pending' ? 'bg-warning text-dark' : '' }}
                            {{ $timesheet->status == 'deleted' ? 'bg-danger' : '' }}">
                                    {{ ucfirst($timesheet->status) }}
                                </span>
                            </td>

                            <td>{{ $timesheet->work_time }}</td>
                            <td class="text-nowrap">
                                <form action="{{ route('timesheet.updateStatus', $timesheet->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <select name="status" class="form-select form-select-sm mb-2">
                                        <option value="pending" {{ $timesheet->status == 'pending' ? 'selected' : '' }}>
                                            Pending</option>
                                        <option value="approved" {{ $timesheet->status == 'approved' ? 'selected' : '' }}>
                                            Approve</option>
                                        <option value="deleted">Delete</option>
                                    </select>
                                    <button type="submit" class="btn btn-success btn-sm"
                                        onclick="return confirm('Are you sure?');">
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
            <div class="p-2">
                {{ $pendingTimesheets->appends(request()->except('pending_page'))->links() }}
            </div>

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
                    @foreach ($approvedTimesheets as $index => $timesheet)
                    <tr>
                        <td>{{ $approvedTimesheets->firstItem() + $index }}</td> <!-- This will display the correct number across paginated pages -->
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
                                <span
                                    class="badge 
                            {{ $timesheet->status == 'approved' ? 'bg-success' : '' }}
                            {{ $timesheet->status == 'pending' ? 'bg-warning text-dark' : '' }}
                            {{ $timesheet->status == 'deleted' ? 'bg-danger' : '' }}">
                                    {{ ucfirst($timesheet->status) }}
                                </span>
                            </td>
                            <td>{{ $timesheet->work_time }}</td>
                            <td class="text-nowrap">
                                <form action="{{ route('timesheet.updateStatus', $timesheet->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <select name="status" class="form-select form-select-sm mb-2">
                                        <option value="pending" {{ $timesheet->status == 'pending' ? 'selected' : '' }}>
                                            Pending</option>
                                        <option value="approved" {{ $timesheet->status == 'approved' ? 'selected' : '' }}>
                                            Approve</option>
                                        <option value="deleted">Delete</option>
                                    </select>
                                    <button type="submit" class="btn btn-success btn-sm"
                                        onclick="return confirm('Are you sure?');">
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
            <div class="p-2">
                {{ $approvedTimesheets->appends(request()->except('approved_page'))->links() }}
            </div>

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

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
        </script>

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
        </script>

        <script>
            function calculateWorkTime() {
                const startTime = document.getElementById("editStartTime").value;
                const closeTime = document.getElementById("editCloseTime").value;
                const breakStart = document.getElementById("editBreakStart").value;
                const breakEnd = document.getElementById("editBreakEnd").value;

                // Check if start and close times are provided
                if (startTime && closeTime) {
                    // Calculate total work hours
                    const start = new Date(`1970-01-01T${startTime}:00`);
                    const close = new Date(`1970-01-01T${closeTime}:00`);
                    let workDuration = (close - start) / (1000 * 60); // Convert from ms to minutes

                    // Check if break times are provided
                    if (breakStart && breakEnd) {
                        const breakStartTime = new Date(`1970-01-01T${breakStart}:00`);
                        const breakEndTime = new Date(`1970-01-01T${breakEnd}:00`);
                        const breakDuration = (breakEndTime - breakStartTime) / (1000 * 60); // Convert from ms to minutes

                        // Subtract break duration from work duration
                        workDuration -= breakDuration;
                    }

                    // Ensure work duration doesn't go negative
                    workDuration = Math.max(workDuration, 0);

                    // Convert work duration to HH:mm format
                    const hours = Math.floor(workDuration / 60);
                    const minutes = workDuration % 60;

                    // Format hours and minutes to ensure two digits
                    const formattedWorkTime = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}`;
                    document.getElementById("WorkTime").value = formattedWorkTime; // Display in HH:mm format
                } else {
                    document.getElementById("WorkTime").value = ""; // Clear if start or close time is missing
                }
            }
        </script>
    @endsection
