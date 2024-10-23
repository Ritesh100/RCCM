<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PaySlip PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 8px;
            border: 1px solid #000;
        }
        th {
            background-color: #f4f4f4;
        }
        h2, h4 {
            color: #333;
        }
    </style>
</head>
<body>
    <h2>Pay Advice from {{$company->name}} to {{$user->name}}</h2>
    <br><br>
    <br>
    <table>
        <tr>
            <th>Address</th>
            <th>Week Range</th>
            <th>Hours Worked</th>
            <th>Hourly Rate</th>
        </tr>
        <tr>
            <td>{{$user->address ?? 'N/A'}}</td>
            <td>{{$payslip->week_range}}</td>
            <td>{{number_format($payslip->hrs_worked, 2)}}</td>
            <td>${{number_format($payslip->hrlyRate, 2)}}</td>
        </tr>
    </table>

    <h4>Gross earning: {{number_format($payslip->hrs_worked, 2)}} * ${{number_format($payslip->hrlyRate, 2)}} = ${{number_format($gross_earning, 2)}}</h4>

    <p>Tax Contribution: applicable in home country under the responsibility of {{$user->name}}</p>

    <p>Super/Employee Provident: Applicable in home country under the responsibility of {{$user->name}}</p>

    <p>Accumulated leave per payslip = 0.073421 per total hrs</p>

    <div style="margin-top: 30px;">
        <p><strong>Timesheet Details:</strong></p>
        <table>
            <tr>
                <th>Date</th>
                <th>Hours Worked</th>
                <th>Start Time</th>
                <th>End Time</th>
            </tr>
            @foreach($timesheets as $timesheet)
            <tr>
                <td>{{$timesheet->date}}</td>
                <td>{{ number_format((float) $timesheet->work_time, 2) }}</td> <!-- Cast to float -->
                <td>{{$timesheet->start_time}}</td>
                <td>{{$timesheet->close_time}}</td>
            </tr>
            @endforeach
        </table>
    </div>
</body>
</html>