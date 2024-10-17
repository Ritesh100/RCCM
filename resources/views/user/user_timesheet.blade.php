@extends('user.sidebar')

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

        .pagination {
            display: flex;
            justify-content: center;
            list-style-type: none;
            padding: 0;
        }

        .pagination li {
            margin: 0 5px;
        }

        .pagination a,
        .pagination span {
            display: block;
            padding: 10px 15px;
            border: 1px solid #007bff;
            /* Bootstrap primary color */
            color: #007bff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .pagination a:hover {
            background-color: #007bff;
            /* Change to Bootstrap primary color on hover */
            color: white;
        }

        .pagination .active span {
            background-color: #007bff;
            color: white;
            border: 1px solid #007bff;
        }

        .pagination .disabled span {
            color: #6c757d;
            /* Bootstrap secondary color for disabled */
            border: 1px solid #6c757d;
        }

        .pagination .ellipsis {
            padding: 10px 15px;
        }
    </style>

    <form action="{{ route('timeSheet.store') }}" method="POST">
        @csrf

        <h3>Timesheet</h3>

        <!-- Week Range Selection -->
        <label for="week_start">Select Week Start:</label>
        <input type="date" name="week_start" id="week_start" required>

        <label for="week_end">Select Week End:</label>
        <input type="date" name="week_end" id="week_end" required>

        <button type="button" onclick="generateTimesheetRows()">Generate Timesheet</button>

        <!-- Table structure to hold timesheet data -->
        <div class="timesheet-container">
            <table id="timesheetTable">
                <thead>
                    <tr>
                        <th>Day</th>
                        <th>Cost Center</th>
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

        <button type="submit">Submit</button>
    </form>

    <h3>Timesheet</h3>

    <table border="1">
        <thead>
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
                    <td>{{ $timesheet->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="pagination">
        {{ $data->links('pagination::bootstrap-4') }}
    </div>
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

                // Create a new row for the current day
                const row = `
                <tr>
                    <td>
                    <!-- Hidden input for the day name -->
                    <input type="hidden" name="day[]" value="${dayName}">${dayName}</td>
                    <td>
                        <select name="cost_center[]" id="time_option_${dateString}">
                            <option value="hrs_worked">Hrs Worked</option>
                            <option value="annual_leave">Annual Leave</option>
                            <option value="sick_leave">Sick Leave</option>
                            <option value="public_holiday">Public Holiday</option>
                            <option value="unpaid_leave">Other Unpaid Leave</option>
                            <option value="paid_leave">Other Paid Leave</option>
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
