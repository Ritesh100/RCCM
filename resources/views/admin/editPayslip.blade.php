a@extends('admin.sidebar')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">


@section('content')
    <div class="container mt-4">
        <div class="card shadow-sm">
           
            <div class="card-header ">
                <h2 class="mb-0">Payslip for {{ $user->name }}</h2>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <p><strong>Company:</strong> {{ $company->name }}</p>
                    <p><strong>Company Address:</strong> {{ $company_address }}</p>
                    <p><strong>Week Range:</strong> {{ $payslip->week_range }}</p>
                </div>

                <h4 class="text-secondary">Timesheet Details</h4>
                <button type="button" class="btn btn-success m-1" data-bs-toggle="modal" data-bs-target="#addTimesheetModal">
                    <i class="fas fa-plus"></i> Add Timesheet
                </button>
                
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>Date</th>
                                <th>Cost Center</th>
                                <th>Work Time</th>
                                <th>Currency</th>
                                <th>Hourly Rate</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($timesheets as $timesheet)
                                <tr>
                                    <td>{{ $timesheet->date }}</td>
                                    <td>{{ $timesheet->cost_center }}</td>
                                    <td>{{ $timesheet->work_time }}</td>
                                    <td>{{ $currency }}</td>
                                    <td>{{ number_format($hourly_rate, 2) }}</td>
                                    <td class="d-flex">

                                        <button type="button" class="btn btn-primary btn-sm me-2" data-bs-toggle="modal"
                                            data-bs-target="#viewTimesheetModal"
                                             data-id="{{ $timesheet->id }}"
                                            data-date="{{ $timesheet->date }}"
                                             data-day="{{ $timesheet->day }}"
                                            data-user_email="{{ $timesheet->user_email }}"
                                            data-timezone="{{ $timesheet->timezone }}"
                                            data-cost_center="{{ $timesheet->cost_center }}"
                                            data-work_time="{{ $timesheet->work_time }}"
                                            data-currency="{{ $currency }}"
                                            data-hourly_rate="{{ number_format($hourly_rate, 2) }}"
                                            data-start_time="{{ $timesheet->start_time }}"
                                            data-close_time="{{ $timesheet->close_time }}"
                                            data-break_start="{{ $timesheet->break_start }}"
                                            data-break_end="{{ $timesheet->break_end }}">
                                            Edit
                                        </button>

  <!-- Delete Button -->
  <form action="{{ route('timesheet.delete', $timesheet->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this record?');">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger btn-sm m-1">Delete</button>
</form>

                                    </td>
                                   
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <h4 class="text-secondary mt-4">Summary</h4>
                <ul class="list-group">
                    <li class="list-group-item"><strong>Total Hours Worked:</strong> {{ $hrs_worked }} hrs</li>
                    <li class="list-group-item"><strong>Gross Earning:</strong> {{ $currency }}
                        {{ number_format($gross_earning, 2) }}</li>
                    <li class="list-group-item"><strong>Annual Leave:</strong> {{ number_format($annual_leave, 2) }} hrs
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- modal for adding -->
    <div class="modal fade" id="addTimesheetModal" tabindex="-1" aria-labelledby="addTimesheetModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTimesheetModalLabel">Add New Timesheet </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="timesheetForm" novalidate >
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="day" class="form-label">Day</label>
                                <select class="form-control" id="day" name="day" required>
                                    <option value="">Select Day</option>
                                    <option value="Sunday">Sunday</option>
                                    <option value="Monday">Monday</option>
                                    <option value="Tuesday">Tuesday</option>
                                    <option value="Wednesday">Wednesday</option>
                                    <option value="Thursday">Thursday</option>
                                    <option value="Friday">Friday</option>
                                    <option value="Saturday">Saturday</option>
                                </select>
                                <div class="invalid-feedback">Please enter a day.</div>
                            </div>
                            <div class="col-md-6">
                                <label for="cost_center" class="form-label">Cost Center</label>
                                <select name="cost_center" class="form-control" id="cost_center" required>
                                    <option value="">Select Cost Center</option>
                                    <option value="hrs_worked">Hrs Worked</option>
                                    <option value="annual_leave">Annual Leave</option>
                                    <option value="sick_leave">Sick Leave</option>
                                    <option value="public_holiday">Public Holiday</option>
                                    <option value="unpaid_leave">Unpaid Leave</option>
                                </select>
                                <div class="invalid-feedback">Please enter a cost center.</div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="date" class="form-label">Date</label>
                                <input type="date" class="form-control" id="date" name="date" required aria-describedby="dateError">
                                <div class="invalid-feedback" id="dateError">Please select a date.</div>
                                
                            </div>
                            <div class="col-md-6">
                                <label for="start_time" class="form-label">Start Time</label>
                                <input type="time" class="form-control" id="start_time" name="start_time"  oninput="calculateAddWorkTime()" required>
                                <div class="invalid-feedback">Please enter a start time.</div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="close_time" class="form-label">Close Time</label>
                                <input type="time" class="form-control" id="close_time" name="close_time"  oninput="calculateAddWorkTime()" required>
                                <div class="invalid-feedback">Please enter a close time.</div>
                            </div>
                            <div class="col-md-6">
                                <label for="break_start" class="form-label">Break Start (Optional)</label>
                                <input type="time" class="form-control" id="break_start" name="break_start"  oninput="calculateAddWorkTime()">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="break_end" class="form-label">Break End (Optional)</label>
                                <input type="time" class="form-control" id="break_end" name="break_end"  oninput="calculateAddWorkTime()">
                            </div>
                            <div class="col-md-6">
                                <label for="work_time" class="form-label">Work Time</label>
                                <input type="text" class="form-control" id="work_time" name="work_time" required>
                                <div class="invalid-feedback"></div>
                            </div>
                            
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="">Select Status</option>
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                </select>
                                <div class="invalid-feedback">Please select a status.</div>
                            </div>
                            <div class="col-md-6">
                                <label for="user_email" class="form-label">User Email</label>
                                <select name="user_email" id="user_email" class="form-control" required>
                                    <option value="">-- Select User --</option>
                                    @forelse($timesheets->unique('user_email') as $timesheet)
                                        <option value="{{ $timesheet->user_email }}"
                                            {{ $payslip->user_email == $timesheet->user_email ? 'selected' : '' }}>
                                            {{ $timesheet->user_email }}
                                        </option>
                                    @empty
                                        <option value="">No users available</option>
                                    @endforelse
                                </select>
                                
                                <div class="invalid-feedback">Please enter a valid email address.</div>
                            </div>
                            
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="reportingTo" class="form-label">Reporting To</label>
                                <input type="text" 
                                class="form-control" 
                                id="reportingTo" 
                                name="reportingTo" 
                                value="{{ $payslip->reportingTo ?? '-- No Reporting To Assigned --' }}" 
                                readonly>
                                <div class="invalid-feedback">Please enter reporting manager.</div>
                            </div>

                            <div class="col-md-6">
                                <label for="timezone" class="form-label">Timezone</label>
                                <select name="timezone" id="timezone" class="form-control" required>
                                    <option value="">Select Timezone</option>
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
                                <div class="invalid-feedback">Please enter a timezone.</div>
                            </div>
                            <div class="col-md-6">
                                <label for="currency" class="form-label">Currency</label>
                                <select name="currency" class="form-control" id="currency" required>
                                    <option value="">Select Currency</option>
                                    <option value="NPR">Nepali Rupee (NPR)</option>
                                    <option value="USD">United States Dollar (USD)</option>
                                    <option value="EUR">Euro (EUR)</option>
                                    <option value="JPY">Japanese Yen (JPY)</option>
                                    <option value="GBP">British Pound Sterling (GBP)</option>
                                    <option value="AUD">Australian Dollar (AUD)</option>
                                </select>
                                <div class="invalid-feedback">Please enter currency.</div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveTimesheetBtn">Save Changes</button>
                </div>
            </div>
        </div>
    </div>
    

    <!-- Modal for editing Timesheet Data -->
    <div class="modal fade" id="viewTimesheetModal" tabindex="-1" aria-labelledby="viewTimesheetModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewTimesheetModalLabel">Timesheet Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="viewTimesheetForm" action="{{ route('admin.updatePayslip', $timesheet->id) }}"
                        method="POST">
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="id" value="{{ $timesheet->id }}">

                        <div class="mb-3">
                            <label for="viewDay" class="form-label"><strong>Day:</strong></label>
                            <select class="form-control" id="viewDay" name="day" required>
                                <option value="">Select Day</option>
                                <option value="Sunday">Sunday</option>
                                <option value="Monday">Monday</option>
                                <option value="Tuesday">Tuesday</option>
                                <option value="Wednesday">Wednesday</option>
                                <option value="Thursday">Thursday</option>
                                <option value="Friday">Friday</option>
                                <option value="Saturday">Saturday</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="viewDate" class="form-label"><strong>Date:</strong></label>
                            <input type="date" class="form-control" id="viewDate" name="date">
                        </div>
                        <div class="mb-3">
                            <label for="viewUserEmail" class="form-label"><strong>User Email:</strong></label>
                            <input type="email" class="form-control" id="viewUserEmail" name="user_email" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="viewCostCenter" class="form-label"><strong>Cost Center:</strong></label>
                            <select name="cost_center" class="form-control" id="viewCostCenter">
                                <option value="hrs_worked">Hrs Worked</option>
                                <option value="annual_leave">Annual Leave</option>
                                <option value="sick_leave">Sick Leave</option>
                                <option value="public_holiday">Public Holiday</option>
                                <option value="unpaid_leave">Unpaid Leave</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="viewStartTime" class="form-label">Start Time</label>
                            <input type="time" class="form-control" id="viewStartTime" name="start_time"
                                oninput="calculateViewWorkTime()">
                        </div>
                        <div class="mb-3">
                            <label for="viewCloseTime" class="form-label">Close Time</label>
                            <input type="time" class="form-control" id="viewCloseTime" name="close_time"
                                oninput="calculateViewWorkTime()">
                        </div>
                        <div class="mb-3">
                            <label for="viewBreakStart" class="form-label"><strong>Break Start:</strong></label>
                            <input type="time" class="form-control" id="viewBreakStart" name="break_start"
                                oninput="calculateViewWorkTime()">
                        </div>
                        <div class="mb-3">
                            <label for="viewBreakEnd" class="form-label"><strong>Break End:</strong></label>
                            <input type="time" class="form-control" id="viewBreakEnd" name="break_end"
                                oninput="calculateViewWorkTime()">
                        </div>
                        <div class="mb-3">
                            <label for="viewWorkTime" class="form-label"><strong>Work Time:</strong></label>
                            <input type="text" class="form-control" id="viewWorkTime" name="work_time" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="viewCurrency" class="form-label"><strong>Currency:</strong></label>
                            <select name="currency" class="form-control" id="viewCurrency">
                                <option value="NPR">Nepali Rupee (NPR)</option>
                                <option value="USD">United States Dollar (USD)</option>
                                <option value="EUR">Euro (EUR)</option>
                                <option value="JPY">Japanese Yen (JPY)</option>
                                <option value="GBP">British Pound Sterling (GBP)</option>
                                <option value="AUD">Australian Dollar (AUD)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="viewHourlyRate" class="form-label"><strong>Hourly Rate:</strong></label>
                            <input type="text" class="form-control" id="viewHourlyRate" name="hourly_rate">
                        </div>
                        <div class="mb-3">
                            <label for="viewTimezone" class="form-label"><strong>Timezone:</strong></label>
                            <select name="timezone" id="viewTimezone" class="form-control">
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
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" form="viewTimesheetForm" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </div>
    </div>



    <!-- Include Bootstrap JS (for Modal functionality) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    
    <script>
  document.addEventListener('DOMContentLoaded', function() {
    // Work time calculation function for add modal
    function calculateAddWorkTime() {
    const startTime = document.getElementById('start_time').value;
    const closeTime = document.getElementById('close_time').value;
    const breakStart = document.getElementById('break_start').value;
    const breakEnd = document.getElementById('break_end').value;

    if (startTime && closeTime) {
        let start = new Date(`2000-01-01T${startTime}`);
        let close = new Date(`2000-01-01T${closeTime}`);
        
        let workDuration = (close - start) / (1000 * 60); // duration in minutes

        // Subtract break time if provided
        if (breakStart && breakEnd) {
            let breakStartTime = new Date(`2000-01-01T${breakStart}`);
            let breakEndTime = new Date(`2000-01-01T${breakEnd}`);
            let breakDuration = (breakEndTime - breakStartTime) / (1000 * 60);
            workDuration -= breakDuration;
        }

        const hours = Math.floor(workDuration / 60);
        const minutes = workDuration % 60;
        
        document.getElementById('work_time').value = 
            `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:00`;
    }
}

    // Event listeners for input fields to trigger work time calculation on change
    document.getElementById('start_time').addEventListener('input', calculateAddWorkTime);
    document.getElementById('close_time').addEventListener('input', calculateAddWorkTime);
    document.getElementById('break_start').addEventListener('input', calculateAddWorkTime);
    document.getElementById('break_end').addEventListener('input', calculateAddWorkTime);




    // Work time calculation function for view modal (existing function)
    function calculateViewWorkTime() {
        const startTime = document.getElementById('viewStartTime').value;
        const closeTime = document.getElementById('viewCloseTime').value;
        const breakStart = document.getElementById('viewBreakStart').value;
        const breakEnd = document.getElementById('viewBreakEnd').value;

        if (startTime && closeTime) {
            const start = new Date(`1970-01-01T${startTime}:00`);
            const close = new Date(`1970-01-01T${closeTime}:00`);
            const breakStartTime = breakStart ? new Date(`1970-01-01T${breakStart}:00`) : null;
            const breakEndTime = breakEnd ? new Date(`1970-01-01T${breakEnd}:00`) : null;

            let totalMinutes = (close - start) / (1000 * 60); // total minutes
            if (breakStartTime && breakEndTime) {
                const breakDuration = (breakEndTime - breakStartTime) / (1000 * 60);
                totalMinutes -= breakDuration;
            }

            const hours = Math.floor(totalMinutes / 60);
            const minutes = Math.round(totalMinutes % 60);
            const decimalHours = hours + (minutes / 60);

            // Format as HH:MM:00 to match your PHP function's expected input
            const formattedWorkTime = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:00`;
            document.getElementById('viewWorkTime').value = formattedWorkTime;
        }
    }

 

    // Retain existing event listeners for view modal
    ['viewStartTime', 'viewCloseTime', 'viewBreakStart', 'viewBreakEnd'].forEach(id => {
        document.getElementById(id).addEventListener('change', calculateViewWorkTime);
    });


            // Modal show event listener
            viewTimesheetModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;

                // Extract data attributes
                const timesheetId = button.getAttribute('data-id');

                        // Update the form's action to include the correct timesheet ID
        const form = viewTimesheetModal.querySelector('#viewTimesheetForm');

        // Set the hidden input to the correct ID
        const hiddenIdInput = viewTimesheetModal.querySelector('input[name="id"]');
        hiddenIdInput.value = timesheetId;

                const date = button.getAttribute('data-date');
                const costCenter = button.getAttribute('data-cost_center');
                const day = button.getAttribute('data-day');
                const userEmail = button.getAttribute('data-user_email');
                const timezone = button.getAttribute('data-timezone');
                const workTime = button.getAttribute('data-work_time');
                const currency = button.getAttribute('data-currency');
                const hourlyRate = button.getAttribute('data-hourly_rate');
                const startTime = button.getAttribute('data-start_time');
                const closeTime = button.getAttribute('data-close_time');
                const breakStart = button.getAttribute('data-break_start');
                const breakEnd = button.getAttribute('data-break_end');

                // Set the data in the modal input fields
                viewTimesheetModal.querySelector('#viewDate').value = date;
                viewTimesheetModal.querySelector('#viewCostCenter').value = costCenter;
                viewTimesheetModal.querySelector('#viewWorkTime').value = workTime;
                viewTimesheetModal.querySelector('#viewCurrency').value = currency;
                viewTimesheetModal.querySelector('#viewHourlyRate').value = hourlyRate;
                viewTimesheetModal.querySelector('#viewStartTime').value = startTime;
                viewTimesheetModal.querySelector('#viewCloseTime').value = closeTime;
                viewTimesheetModal.querySelector('#viewDay').value = day;
                viewTimesheetModal.querySelector('#viewUserEmail').value = userEmail;
                viewTimesheetModal.querySelector('#viewTimezone').value = timezone;
                viewTimesheetModal.querySelector('#viewBreakStart').value = breakStart;
                viewTimesheetModal.querySelector('#viewBreakEnd').value = breakEnd;
            });
        });

    

        // Event listeners for the modal fields
        document.getElementById('viewStartTime').addEventListener('change', function() {
            const startTime = this.value;
            const closeTime = document.getElementById('viewCloseTime').value;
            const breakStart = document.getElementById('viewBreakStart').value;
            const breakEnd = document.getElementById('viewBreakEnd').value;

            calculateModalWorkTime(startTime, closeTime, breakStart, breakEnd);
        });

        document.getElementById('viewCloseTime').addEventListener('change', function() {
            const closeTime = this.value;
            const startTime = document.getElementById('viewStartTime').value;
            const breakStart = document.getElementById('viewBreakStart').value;
            const breakEnd = document.getElementById('viewBreakEnd').value;

            calculateModalWorkTime(startTime, closeTime, breakStart, breakEnd);
        });

        document.getElementById('viewBreakStart').addEventListener('change', function() {
            const breakStart = this.value;
            const startTime = document.getElementById('viewStartTime').value;
            const closeTime = document.getElementById('viewCloseTime').value;
            const breakEnd = document.getElementById('viewBreakEnd').value;

            calculateModalWorkTime(startTime, closeTime, breakStart, breakEnd);
        });

        document.getElementById('viewBreakEnd').addEventListener('change', function() {
            const breakEnd = this.value;
            const startTime = document.getElementById('viewStartTime').value;
            const closeTime = document.getElementById('viewCloseTime').value;
            const breakStart = document.getElementById('viewBreakStart').value;

            calculateModalWorkTime(startTime, closeTime, breakStart, breakEnd);
        });

        document.getElementById('saveTimesheetBtn').addEventListener('click', function(event) {
    event.preventDefault(); // Prevent default form submission
    
    // Validate form
    const form = document.getElementById('timesheetForm');
    if (!form.checkValidity()) {
        form.classList.add('was-validated');
        return;
    }

    const formData = new FormData(form);
    
    fetch('/admin/add-payslip', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify(Object.fromEntries(formData))
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        console.log(data.message);
        
        // Close the modal after saving
        var modal = new bootstrap.Modal(document.getElementById('addTimesheetModal'));
        modal.hide();
        
        // Assuming you have userId and weekRange in the response or accessible through the form
        const userId = data.userId; // You can modify this depending on how you get the userId
        const weekRange = data.weekRange; // Modify this to fetch the weekRange if needed
        
        // Redirect to the editPayslip page
        window.location.href = document.referrer; // This will take the user back to the page they came from
        
        // Optionally refresh the timesheet list or show a success message
    })
    .catch(error => {
        console.error('Error:', error);
        // Handle errors, show error message
    });
});

    </script>
    
@endsection
