@extends('user.sidebar')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

@section('content')
    <style>
        /* Basic styling for the form and table */
        form {
            width: 100%;
            margin-top: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input,
        select {
            margin-bottom: 15px;
            padding: 5px;
            width: 100%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        hr {
            margin: 20px 0;
        }

        .timesheet-container {
            margin: 0 auto;
        }

       
    </style>

<div class="containe-fluid">
    <h1 class="mb-4 text-center">Timesheet Management</h1>
      
    

    <form action="{{ route('timeSheet.store') }}" method="POST">
        @csrf
        <!-- Week Range Selection -->
        <div class="date-range-section p-4 shadow rounded bg-light mx-auto mb-2"  style="max-width: 600px;"> <!-- Added shadow and background -->
            <h4 class="text-center mb-4">Generate Timesheet</h4>
            <div class="row g-3 align-items-center">
                <div class="col-md-6">
                    <label for="week_start" class="form-label">Select Week Start:</label>
                    <input type="date" name="week_start" id="week_start" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label for="week_end" class="form-label">Select Week End:</label>
                    <input type="date" name="week_end" id="week_end" class="form-control" required>
                </div>
            </div>
            <div class="text-center"> <!-- Center the button below inputs -->
                <button type="button" class="btn btn-primary" onclick="generateTimesheetRows()">
                    <i class="fas fa-calendar-alt me-2"></i> Generate Timesheet
                </button>
            </div>
        </div>
        <div class="export-buttons text-end justify-content-end g-1 text-nowrap mb-1" >
            <a href="{{ route('timesheet.export.approved') }}" class="btn btn-success btn-sm">
                <i class="fas fa-file-excel me-2"></i>Export Approved
            </a>
            <a href="{{ route('timesheet.export.pending') }}" class="btn btn-warning btn-sm">
                <i class="fas fa-file-excel me-2"></i>Export Pending
            </a>
            <a href="{{ route('timesheet.export.all') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-file-excel me-2"></i>Export All
            </a>
            </div>
        <!-- Table structure to hold timesheet data -->
        <div class="timesheet-container">
            <table  border="1">
                <thead>
                    <tr>
                        <th>Day</th>
                        <th>Reporting To</th>
                        <th>Cost Center</th>
                        <th>Currency</th>
                        <th>Date</th>
                        <th>Start Time</th>
                        <th>Close Time</th>
                        <th>Break Start</th>
                        <th>Break End</th>
                        <th>Timezone</th>
                        <th>Work Time</th>
                    </tr>
                </thead>
                <tbody id="timesheetRows">
                    <!-- Rows will be dynamically inserted here -->
                </tbody>
            </table>
        </div>

        <button type="submit" class="btn btn-primary mt-3" >Submit</button>
    </form>
    <h3>Timesheet</h3>


    <form method="GET" action="{{ route('user.timeSheet') }}" class="input-group" style="max-width: 1000px;">
        <select name="day" class="form-select me-2 filter-select mb-2">
            <option value="">Select Day</option>
            @foreach($days as $day)
                <option value="{{ $day }}" {{ request('day') == $day ? 'selected' : '' }}>{{ $day }}</option>
            @endforeach
        </select>

        <select name="cost_center" class="form-select me-2 filter-select mb-2">
            <option value="">Select Cost Center</option>
            @foreach($costCenters as $costCenter)
                <option value="{{ $costCenter }}" {{ request('cost_center') == $costCenter ? 'selected' : '' }}>{{ $costCenter }}</option>
            @endforeach
        </select>

        <select name="date" class="form-select me-2 filter-select mb-2">
            <option value="">Select Date</option>
            @foreach($dates as $date)
                <option value="{{ $date }}" {{ request('date') == $date ? 'selected' : '' }}>{{ $date }}</option>
            @endforeach
        </select>

        <select name="status" class="form-select me-2 filter-select mb-2">
            <option value="">Select Status</option>
            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
        </select>

    <button type="submit" class="btn btn-primary rounded-pill ms-2 mb-2">Filter</button>
    <button type="button" class="btn btn-secondary rounded-pill ms-2 mb-2" onClick="window.location.href='{{ route('user.timeSheet') }}'">Reset</button>
</form>

    <div class="timesheet-container">
    <table border="1">
        <thead>
            <tr>
                <th>S.N.</th>
                <th>Day</th>
                <th>Reporting To</th>
                <th>Cost Center</th>
                <th>Currency</th>
                <th>Date</th>
                <th>Start Time</th>
                <th>Close Time</th>
                <th>Break Start</th>
                <th>Break End</th>
                <th>Timezone</th>
                <th>Work Time</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $index => $timesheet)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $timesheet->day }}</td>
                    <td>{{ $timesheet->reportingTo }}</td>
                    <td>{{ $timesheet->cost_center }}</td>
                    <td>{{ $timesheet->currency }}</td>
                    <td>{{ $timesheet->date }}</td>
                    <td>{{ $timesheet->start_time }}</td>
                    <td>{{ $timesheet->close_time }}</td>
                    <td>{{ $timesheet->break_start }}</td>
                    <td>{{ $timesheet->break_end }}</td>
                    <td>{{ $timesheet->timezone }}</td>
                    <td>{{ $timesheet->work_time }}</td>
                    <td>
                        <span
                        class="badge 
                {{ $timesheet->status == 'approved' ? 'bg-success' : '' }}
                {{ $timesheet->status == 'pending' ? 'bg-warning text-dark' : '' }}
                {{ $timesheet->status == 'deleted' ? 'bg-danger' : '' }}">
                        {{ ucfirst($timesheet->status) }}
                    </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    </div>
    <div class="pagination">
        {{ $data->links() }}
    </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    
    <script>
        function generateTimesheetRows() {
            const weekStart = document.getElementById('week_start').value;
            const weekEnd = document.getElementById('week_end').value;

            if (!weekStart || !weekEnd) {
                alert("Please select a valid week range.");
                return;
            }

            // Parse the start and end dates
            const startDate = new Date(weekStart);
            const endDate = new Date(weekEnd);
            const timesheetRowsDiv = document.getElementById('timesheetRows');

            // Clear any previous rows
            timesheetRowsDiv.innerHTML = '';

            // Generate rows for each day in the selected range
            let currentDate = startDate;
            while (currentDate <= endDate) {
                const dayName = currentDate.toLocaleString('en-US', {
                    weekday: 'long'
                });
                const dateString = currentDate.toISOString().split('T')[0];

                const reportingTo = @json($reporting_to);

                // Create a new row for the current day
                const row = `
                <tr>
                    <td>
                    <!-- Hidden input for the day name -->
                    <input type="hidden" name="day[]" value="${dayName}">${dayName}</td>
                    <td><input type="hidden" name="reportingTo[]" value="${reportingTo}">${reportingTo}</td>
                    <td>
                        <select name="cost_center[]" id="time_option_${dateString}">
                            <option value="hrs_worked">Hrs Worked</option>
                            <option value="annual_leave">Annual Leave</option>
                            <option value="sick_leave">Sick Leave</option>
                            <option value="public_holiday">Public Holiday</option>
                        </select>
                    </td>
                    <td>
                         <select name="currency[]" id="currency_${dateString}">
                            <option value="NPR">Nepali Rupee (NPR)</option>
                            <option value="USD">United States Dollar (USD)</option>
                            <option value="EUR">Euro (EUR)</option>
                            <option value="JPY">Japanese Yen (JPY)</option>
                            <option value="GBP">British Pound Sterling (GBP)</option>
                            <option value="AUD">Australian Dollar (AUD)</option>
                         </select>
                    </td>
                    <td><input type="date" name="date[]" id="date_${dateString}" value="${dateString}" readonly></td>
                    <td><input type="time" name="start_time[]" id="start_time_${dateString}" required onchange="calculateWorkTime('${dateString}')"></td>
                    <td><input type="time" name="close_time[]" id="close_time_${dateString}" required onchange="calculateWorkTime('${dateString}')"></td>
                    <td><input type="time" name="break_start[]" id="break_start_${dateString}" onchange="calculateWorkTime('${dateString}')"></td>
                    <td><input type="time" name="break_end[]" id="break_end_${dateString}" onchange="calculateWorkTime('${dateString}')"></td>
                    <td>
                        <select name="timezone[]" id="timezone_${dateString}">
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
                    </td>
                    <td><input type="text" name="work_time[]" id="work_time_${dateString}" readonly></td>
                </tr>
            `;

                // Append the new row to the table body
                timesheetRowsDiv.innerHTML += row;

                // Move to the next day
                currentDate.setDate(currentDate.getDate() + 1);
            }
        }
    </script>

    <script>
        function calculateWorkTime(dateString) {
            const startTimeInput = document.getElementById(`start_time_${dateString}`);
            const closeTimeInput = document.getElementById(`close_time_${dateString}`);
            const breakStartInput = document.getElementById(`break_start_${dateString}`);
            const breakEndInput = document.getElementById(`break_end_${dateString}`);
            const workTimeInput = document.getElementById(`work_time_${dateString}`);

            const startTime = startTimeInput.value;
            const closeTime = closeTimeInput.value;
            const breakStart = breakStartInput.value;
            const breakEnd = breakEndInput.value;

            if (startTime && closeTime) {
                // Calculate total work time without break
                const start = new Date(`1970-01-01T${startTime}Z`);
                const close = new Date(`1970-01-01T${closeTime}Z`);
                let totalWorkTime = (close - start) / (1000 * 60); // convert to minutes

                // Subtract break time if both break start and break end are provided
                if (breakStart && breakEnd) {
                    const breakStartDate = new Date(`1970-01-01T${breakStart}Z`);
                    const breakEndDate = new Date(`1970-01-01T${breakEnd}Z`);
                    totalWorkTime -= (breakEndDate - breakStartDate) / (1000 * 60); // convert to minutes
                }

                // Convert total work time from minutes to hours and minutes (HH:mm)
                const hours = Math.floor(totalWorkTime / 60);
                const minutes = Math.floor(totalWorkTime % 60);
                workTimeInput.value = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}`;
            } else {
                workTimeInput.value = ''; // Clear work time if inputs are missing
            }
        }
    </script>
@endsection
