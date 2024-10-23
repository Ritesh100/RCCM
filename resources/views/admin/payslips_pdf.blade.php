<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

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
    
    .company-details, .employee-details {
        padding: 15px; /* Add padding */
        border: 1px solid #ddd; /* Add a light border */
        border-radius: 5px; /* Rounded corners */
    }


    </style>
</head>
<body>
  
    <div class="container">
        <div class="row">
            <div class="col-md-6 company-details">
                <h3>Company Details:</h3>
                <p><strong>Company Name:</strong> {{$company->name}}</p>
                <p><strong>Company Address:</strong> {{$company_address}}</p>
                <p><strong>Contact Email:</strong> {{$company->email}}</p>
            </div>
            <div class="col-md-6 employee-details">
                <h3>Employee Details:</h3>
                <p><strong>Name:</strong> {{$user->name}}</p>
                <p><strong>Email:</strong> {{$user->email}}</p>
                <p><strong>Address:</strong> {{$user->address ?? 'N/A'}}</p>
            </div>
        </div>
    </div>
    

    <div class="earnings-section">
        <table>
            <tr>
                <th>Week Range</th>
                <th>Hours Worked</th>
                <th>Currency</th>
                <th>Hourly Rate</th>
                <th>Gross Earnings</th>
            </tr>
            <tr>
                <td>{{$payslip->week_range}}</td>
                <td>{{number_format($payslip->hrs_worked, 2)}}</td>
                <td>{{$currency}}</td>
                <td>{{number_format($payslip->hrlyRate, 2)}}</td>
                <td>{{$currency}} {{number_format($gross_earning, 2)}}</td>
            </tr>
        </table>

        <p><strong>Accumulated Annual Leave:</strong> 0.073421 × {{number_format($payslip->hrs_worked, 2)}} = {{$annual_leave}} hrs</p>
        <p><strong>Total Earnings:</strong> {{number_format($payslip->hrs_worked, 2)}} × {{number_format($payslip->hrlyRate, 2)}} = {{$currency}} {{number_format($gross_earning, 2)}}</p>
    </div>
    <i>Tax Contribution: Applicable in home country under the responsibility of {{$user->name}}.</i><br>

    <i>Superannuation/Employee Provident Fund: Applicable in home country under the responsibility of {{$user->name}}.</i>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>