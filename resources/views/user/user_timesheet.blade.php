@extends('user.sidebar')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

@section('content')


   
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
       
        .week-range-container, .timesheet-container, .timesheet-record {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 1px 3px rgba(0, 0, 0, 0.08);
            padding: 20px;
            margin-bottom: 20px;
        }
        .table th {
            background-color: #f8f9fa;
        }
        .pagination {
            justify-content: center;
        }
        .pagination .page-item .page-link {
            color: #007bff;
            border-color: #007bff;
        }
        .pagination .page-item.active .page-link {
            background-color: #007bff;
            border-color: #007bff;
        }
        @media (max-width: 768px) {
            .table-responsive {
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h3 class="mb-4">Timesheet</h3>
        
        <form action="{{ route('timeSheet.store') }}" method="POST">
            @csrf
            <div class="week-range-container">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="week_start" class="form-label">Week Start:</label>
                        <input type="date" name="week_start" id="week_start" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label for="week_end" class="form-label">Week End:</label>
                        <input type="date" name="week_end" id="week_end" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <button type="button" class="btn btn-primary w-100" onclick="generateTimesheetRows()">Generate Timesheet</button>
                    </div>
                </div>
            </div>

            <div class="timesheet-container">
                <div class="table-responsive">
                    <table id="timesheetTable" class="table table-hover table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Day</th>
                                <th>Cost Center</th>
                                <th>Date</th>
                                <th>Start Time</th>
                                <th>Close Time</th>
                                <th>Break Start</th>
                                <th>Break End</th>
                                <th>Timezone</th>
                            </tr>
                        </thead>
                        <tbody id="timesheetRows">
                            <!-- Rows will be dynamically inserted here -->
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="d-grid gap-2 col-md-6 mx-auto">
                <button type="submit" class="btn btn-success">Submit Timesheet</button>
            </div>
        </form>
        <h3 class="mt-5 mb-4">Timesheet Records</h3>

<div class="timesheet-record">
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
                            <td>{{ $timesheet->status}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
        <div class="d-flex justify-content-center">
            {{ $data->links('pagination::bootstrap-4') }}
        </div>
    </div>
@endsection
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
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
                    <td>
                        <input type="hidden" name="day[]" value="${dayName}">${dayName}
                    </td>
                    <td>
                        <select name="cost_center[]" id="time_option_${dateString}" class="form-select">
                            <option value="hrs_worked">Hrs Worked</option>
                            <option value="annual_leave">Annual Leave</option>
                            <option value="sick_leave">Sick Leave</option>
                            <option value="public_holiday">Public Holiday</option>
                            <option value="unpaid_leave">Other Unpaid Leave</option>
                            <option value="paid_leave">Other Paid Leave</option>
                        </select>
                    </td>
                    <td><input type="date" name="date[]" id="date_${dateString}" value="${dateString}" class="form-control" readonly></td>
                    <td><input type="time" name="start_time[]" id="start_time_${dateString}" class="form-control" required></td>
                    <td><input type="time" name="close_time[]" id="close_time_${dateString}" class="form-control" required></td>
                    <td><input type="time" name="break_start[]" id="break_start_${dateString}" class="form-control"></td>
                    <td><input type="time" name="break_end[]" id="break_end_${dateString}" class="form-control"></td>
                    <td><input type="text" name="timezone[]" id="timezone_${dateString}" class="form-control"></td>
                </tr>
            `;

                timesheetRowsDiv.innerHTML += row;
                currentDate.setDate(currentDate.getDate() + 1);
            }
        }
    </script>

