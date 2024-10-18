@extends('user.sidebar')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

@section('content')
<div class="container-fluid py-4">
    <!-- Add custom styles for table behavior -->
    <style>
        .table-fixed-layout {
            table-layout: fixed;
            width: 100%;
        }
        
        .table-fixed-layout th,
        .table-fixed-layout td {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .table-wrapper {
            max-width: 100%;
            overflow-x: auto;
        }
        
        /* Column widths */
        .col-day { width: 100px; }
        .col-cost-center { width: 150px; }
        .col-date { width: 120px; }
        .col-time { width: 100px; }
        .col-timezone { width: 150px; }
        .col-work-time { width: 100px; }
        
        /* Form control sizing */
        .table-fixed-layout .form-control,
        .table-fixed-layout .form-select {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
        
        /* Ensure inputs don't grow beyond their cells */
        .table-fixed-layout input,
        .table-fixed-layout select {
            width: 100%;
            max-width: 100%;
        }
    </style>

    <!-- Timesheet Form -->
    <div class="p-3 shadow-sm mb-4">
        <div class="card-header bg-primary text-white p-1">
            <h5 class="card-title mb-0">Create Timesheet</h5>
        </div>
            <form action="{{ route('timeSheet.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="week_start" class="form-label">Week Start</label>
                        <input type="date" class="form-control" name="week_start" id="week_start" required>
                    </div>
                    <div class="col-md-6">
                        <label for="week_end" class="form-label">Week End</label>
                        <input type="date" class="form-control" name="week_end" id="week_end" required>
                    </div>
                    <div class="col-12">
                        <button type="button" class="btn btn-secondary" onclick="generateTimesheetRows()">
                            Generate Timesheet
                        </button>
                    </div>
                </div>

                <!-- Timesheet Table -->
                <div class="table-wrapper">
                    <table class="table table-bordered table-hover table-fixed-layout" id="timesheetTable">
                        <thead class="table-light">
                            <tr>
                                <th class="col-day">Day</th>
                                <th class="col-cost-center">Cost Center</th>
                                <th class="col-date">Date</th>
                                <th class="col-time">Start Time</th>
                                <th class="col-time">Close Time</th>
                                <th class="col-time">Break Start</th>
                                <th class="col-time">Break End</th>
                                <th class="col-timezone">Timezone</th>
                                <th class="col-work-time">Work Time</th>
                            </tr>
                        </thead>
                        <tbody id="timesheetRows">
                            <!-- Dynamic rows will be inserted here -->
                        </tbody>
                    </table>
                </div>

                <div class="text-end mt-3">
                    <button type="submit" class="btn btn-primary">Submit Timesheet</button>
                </div>
            </form>
    </div>

    <!-- Existing Timesheets -->
    <div class="p-3 shadow-sm">
        <div class="card-header bg-primary text-white p-1">
            <h5 class="card-title mb-0">Existing Timesheets</h5>
        </div>
        <div class="card-body">
            <div class="table-wrapper">
                <table class="table table-striped table-hover table-fixed-layout">
                    <thead>
                        <tr>
                            <th style="width: 50px">S.N.</th>
                            <th class="col-day">Day</th>
                            <th class="col-cost-center">Cost Center</th>
                            <th class="col-date">Date</th>
                            <th class="col-time">Start Time</th>
                            <th class="col-time">Close Time</th>
                            <th class="col-time">Break Start</th>
                            <th class="col-time">Break End</th>
                            <th class="col-timezone">Timezone</th>
                            <th class="col-work-time">Work Time</th>
                            <th style="width: 100px">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $index => $timesheet)
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
                                <td>{{ $timesheet->work_time }}</td>
                                <td>
                                    <span class="badge bg-{{ $timesheet->status === 'Approved' ? 'success' : 'warning' }}">
                                        {{ $timesheet->status }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-4">
                {{ $data->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>

<script>
    function generateTimesheetRows() {
        const weekStart = document.getElementById('week_start').value;
        const weekEnd = document.getElementById('week_end').value;

        if (!weekStart || !weekEnd) {
            alert("Please select a valid week range.");
            return;
        }

        const startDate = new Date(weekStart);
        const endDate = new Date(weekEnd);
        const timesheetRowsDiv = document.getElementById('timesheetRows');
        
        timesheetRowsDiv.innerHTML = '';

        let currentDate = startDate;
        while (currentDate <= endDate) {
            const dayName = currentDate.toLocaleString('en-US', { weekday: 'long' });
            const dateString = currentDate.toISOString().split('T')[0];

            const row = `
                <tr>
                    <td class="col-day">
                        <input type="hidden" name="day[]" value="${dayName}">
                        ${dayName}
                    </td>
                    <td class="col-cost-center">
                        <select class="form-select form-select-sm" name="cost_center[]" id="time_option_${dateString}">
                            <option value="hrs_worked">Hrs Worked</option>
                            <option value="annual_leave">Annual Leave</option>
                            <option value="sick_leave">Sick Leave</option>
                            <option value="public_holiday">Public Holiday</option>
                            <option value="unpaid_leave">Other Unpaid Leave</option>
                            <option value="paid_leave">Other Paid Leave</option>
                        </select>
                    </td>
                    <td class="col-date">
                        <input type="date" class="form-control form-control-sm" name="date[]" value="${dateString}" readonly>
                    </td>
                    <td class="col-time">
                        <input type="time" class="form-control form-control-sm" name="start_time[]" required 
                            onchange="calculateWorkTime('${dateString}')">
                    </td>
                    <td class="col-time">
                        <input type="time" class="form-control form-control-sm" name="close_time[]" required 
                            onchange="calculateWorkTime('${dateString}')">
                    </td>
                    <td class="col-time">
                        <input type="time" class="form-control form-control-sm" name="break_start[]" 
                            onchange="calculateWorkTime('${dateString}')">
                    </td>
                    <td class="col-time">
                        <input type="time" class="form-control form-control-sm" name="break_end[]" 
                            onchange="calculateWorkTime('${dateString}')">
                    </td>
                    <td class="col-timezone">
                        <select class="form-select form-select-sm" name="timezone[]">
                            <option value="Australia/Sydney">Sydney (AEST)</option>
                            <option value="Australia/Melbourne">Melbourne (AEST)</option>
                            <option value="Australia/Brisbane">Brisbane (AEST)</option>
                            <option value="Australia/Perth">Perth (AWST)</option>
                            <option value="Australia/Adelaide">Adelaide (ACST)</option>
                            <option value="Australia/Darwin">Darwin (ACST)</option>
                            <option value="Australia/Hobart">Hobart (AEST)</option>
                        </select>
                    </td>
                    <td class="col-work-time">
                        <input type="text" class="form-control form-control-sm" name="work_time[]" readonly>
                    </td>
                </tr>
            `;
            timesheetRowsDiv.innerHTML += row;
            currentDate.setDate(currentDate.getDate() + 1);
        }
    }
        function calculateWorkTime(dateString) {
            const row = document.querySelector(`tr:has(input[value="${dateString}"])`);
            const startTime = row.querySelector('input[name="start_time[]"]').value;
            const closeTime = row.querySelector('input[name="close_time[]"]').value;
            const breakStart = row.querySelector('input[name="break_start[]"]').value;
            const breakEnd = row.querySelector('input[name="break_end[]"]').value;
            const workTimeInput = row.querySelector('input[name="work_time[]"]');

            if (startTime && closeTime) {
                const start = new Date(`1970-01-01T${startTime}`);
                const close = new Date(`1970-01-01T${closeTime}`);
                let totalMinutes = (close - start) / (1000 * 60);

                if (breakStart && breakEnd) {
                    const breakStartTime = new Date(`1970-01-01T${breakStart}`);
                    const breakEndTime = new Date(`1970-01-01T${breakEnd}`);
                    const breakMinutes = (breakEndTime - breakStartTime) / (1000 * 60);
                    
                    if (breakMinutes > totalMinutes) {
                        workTimeInput.value = 'Invalid Break';
                        return;
                    }
                    totalMinutes -= breakMinutes;
                }

                totalMinutes = Math.max(totalMinutes, 0);
                const hours = Math.floor(totalMinutes / 60);
                const minutes = Math.floor(totalMinutes % 60);
                workTimeInput.value = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}`;
            } else {
                workTimeInput.value = '';
            }
        }
    </script>
@endsection
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
