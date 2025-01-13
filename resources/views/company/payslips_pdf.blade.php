<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">

    <title>PaySlip PDF</title>
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
    <h2>Pay Advice from {{$admin->userName}}-{{$company->name}} </h2>
    <h3> To: {{$user->name}}</h3>
    <h3>Address: {{$user->address ?? 'N/A'}}</h3>
    <h3>Payslip from {{$payslip->week_range}}</h3>

    <table>
        <tr>
            <th>Date</th>
            <th>Cost Center</th>
            <th>Work Time</th>
            <th>Currency</th>
            <th>Hourly Rate</th>
        </tr>
        @foreach($timesheets as $timesheet)
        <tr>
            <td>{{ $timesheet->date}} </td>
            <td>{{ $timesheet->cost_center }}</td>
            <td>{{ $timesheet->work_time }}</td>
            <td>{{$currency}}</td>
            <td>{{number_format($payslip->hrlyRate, 2)}}</td>
        </tr>
        @endforeach
    </table>
    <h4>Total Work Time: {{number_format($payslip->hrs_worked, 2)}} </h4>
    {{-- <h4><strong>Accumulated Annual Leave per payslip: </strong> 0.073421 x {{number_format($payslip->hrs_worked, 2)}} = {{$annual_leave}} hrs.</h4> --}}


    <h4>Total Earnings: {{number_format($payslip->hrs_worked, 2)}} * {{number_format($payslip->hrlyRate, 2)}} =NPR {{number_format($gross_earning, 2)}}</h4>

    <i>Tax Contribution: Applicable in home country under the responsibility of {{$user->name}}.</i><br>
    <i>Superannuation/Employee Provident Fund: Applicable in home country under the responsibility of {{$user->name}}.</i>


   
</body>
</html>