@extends('user.sidebar')
@section('content')
    <div class="container">
        <h1>Payslips</h1>

        @if(isset($dateRanges) && count($dateRanges) > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>Start Date</th>
                        <th>End Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dateRanges as $range)
                        <tr>
                            <td>{{ $range['start'] }}</td>
                            <td>{{ $range['end'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No payslip data available for this user.</p>
        @endif
    </div>
@endsection