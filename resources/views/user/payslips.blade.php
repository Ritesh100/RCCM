@extends('user.sidebar')

@section('content')
<style>
    a{
        text-decoration: none;
    }
</style>
    <div class="container">
        <h1>Payslips</h1>

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
                                <a href="{{ route('user.document', ['start' => $range['start'], 'end' => $range['end']]) }}">
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
    </div>
@endsection
