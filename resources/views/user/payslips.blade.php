@extends('user.sidebar')

@section('content')
    <style>
        a {
            text-decoration: none;
        }
    </style>
    <div class="container">
        <h1>Payslips</h1>

        @if (isset($noDataMessage))
            <div class="alert alert-warning">
                {{ $noDataMessage }}
            </div>
        @else
            @if (isset($dateRanges) && count($dateRanges) > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>Week Range</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dateRanges as $range)
                            <tr>
                                <td>
                                    <a
                                        href="{{ route('user.payslipsPdf', ['start' => $range['start'], 'end' => $range['end']]) }}" target="_blank">
                                        {{ $range['start'] }} - {{ $range['end'] }} 
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No payslip data available for this user.</p>
            @endif
            @endif
    </div>
@endsection
