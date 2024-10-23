<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PaySlip PDF</title>
</head>
<body>
    <h2>Pay Advice from {{$abn}} to {{$user_name}}</h2>  
    <table border="1">
        <tr>
            <th>Address</th>
            <th>Week Range</th>
            <th>Hours Worked</th>
            <th>Hourly Rate</th>
        </tr>
        <tr>
            <td>{{$user_address}}</td>
            <td>{{$start_date}} - {{$end_date}}</td>
            <td>{{$hrs_worked}}</td>
            <td>{{$hourly_rate}}</td>
        </tr>
    </table>

    <h4>Gross earning :{{$hrs_worked}} * {{$hourly_rate}} = {{$gross_earning}}</h4>

    <p>Tax Contribution : applicable in home country under the responsibility of {{$user_name}}</p>

    <p>Super/Employee Provident : Applicable in home country under the responsibility of {{$user_name}}</p>

    <p>Accumulated leave per payslip = 0.073421 per total hrs</p>
</body>
</html>
