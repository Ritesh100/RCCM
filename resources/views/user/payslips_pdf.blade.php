<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PaySlip PDF</title>
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        h2 {
            text-align: center;
            color: #0056b3;
        }
        h4 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        p {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h2>Pay Advice from {{$admin}} </h2>

    <h3>To: {{$user_name}}</h3>
    <h3>Address: {{$user_address}}</h3>
    <h3>Payslip from {{ $start_date }} to {{ $end_date }}</h3>

    <table>
        <tr>
            <th>Date</th>
            <th>Cost Center</th>
            <th>Work Time</th>
            <th>Currency</th>
            <th>Hourly Rate</th>
        </tr>
            @foreach ($timeSheets as $timeSheet)
            <tr>

            <td>{{ $timeSheet->date }}</td>
            <td>{{ $timeSheet->cost_center }}</td>
            <td>{{ $timeSheet->work_time }}</td>
            <td>{{$currency}}</td>
            <td>{{$hourly_rate}}</td>
        </tr>

            @endforeach
    </table>
    <h4>Total Work Time: {{$hrs_worked}} </h4>
    {{-- <h4><strong>Accumulated Annual Leave per Payslip:</strong> 0.073421 x {{$hrs_worked}} = {{$annual_leave}} hrs.</h4> --}}

    <h4>Total Earnings: {{$hrs_worked}} hours x {{$hourly_rate}} = <strong>{{$currency}}:{{$gross_earning}}</strong></h4>

    <i>Tax Contribution: Applicable in home country under the responsibility of {{$user_name}}.</i><br>

    <i>Superannuation/Employee Provident Fund: Applicable in home country under the responsibility of {{$user_name}}.</i>

</body>
</html>
