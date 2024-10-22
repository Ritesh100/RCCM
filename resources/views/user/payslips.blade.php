@extends('user.payslips')

@section('content')
<h3>Payslips List</h3>
<table border="1">
    <tr>
        <th>S.N.</th>
        <th>Week Range</th>
        <th>Status</th>
    </tr>
    @foreach ($dateRanges as $key => $dateRange)
    <tr>
        <td>{{++$key}}</td>
        <td>{{$dateRange['start']}} - {{$dateRange['end']}}</td>
        <td>Approved</td>
    </tr>
    @endforeach
    
</table>
@endsection