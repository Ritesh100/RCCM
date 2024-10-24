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
    <h2>Pay Advice from {{$company->name}} </h2>
    <h3> To: {{$user->name}}</h3>
    <h3>Address: {{$user->address ?? 'N/A'}}</h3>
   
   
        <table>
            <tr>
                <th>Week Range</th>
                <th>Hours Worked</th>
                <th>Currency</th>
                <th>Hourly Rate</th>
            </tr>
            <tr>
                <td>{{$payslip->week_range}}</td>
                <td>{{number_format($payslip->hrs_worked, 2)}}</td>
                <td>{{$currency}}</td>
                <td>{{number_format($payslip->hrlyRate, 2)}}</td>
            </tr>
        </table>

        <h4><strong>Accumulated Annual Leave per payslip:</strong> 0.073421 × {{number_format($payslip->hrs_worked, 2)}} = {{$annual_leave}} hrs</h4>
        <h4><strong>Total Earnings:</strong> {{number_format($payslip->hrs_worked, 2)}} × {{number_format($payslip->hrlyRate, 2)}} = {{$currency}} {{number_format($gross_earning, 2)}}</h4>
    
    <i>Tax Contribution: Applicable in home country under the responsibility of {{$user->name}}.</i><br>
    <i>Superannuation/Employee Provident Fund: Applicable in home country under the responsibility of {{$user->name}}.</i>

</body>
</html>
