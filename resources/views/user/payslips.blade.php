@extends('user.sidebar')

@section('content')
    <style>
        a {
            text-decoration: none;
        }

        .search-box {
            margin-bottom: 20px;
        }

        .no-data {
            padding: 20px;
            text-align: center;
            color: #6c757d;
        }
    </style>

<div class="containe-fluid">
    <h1 class="mb-4 text-center">PaySlips</h1> 
        @if (isset($noDataMessage))
            <div class="alert alert-warning">
                {{ $noDataMessage }}
            </div>
        @else
            @if (isset($dateRanges) && count($dateRanges) > 0)
                <table class="table">
                    <thead class="text-nowrap">
                        <tr>
                            <th>Week Range</th>
                            <th>Hours Worked</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dateRanges as $range)
                            <tr>
                                <td>{{ $range['start'] }} - {{ $range['end'] }}</td>
                                <td>{{ $range['hours'] }} hrs</td>
                                <td class="text-end">
                                    <a href="{{ route('user.payslipsPdf', ['start' => $range['start'], 'end' => $range['end']]) }}" class="btn btn-sm btn-primary" target="_blank">
                                        <i class="fas fa-file-alt"></i> View Payslip
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="no-data">
                    <h3>No Results Found</h3>
                    <p>No payslip data available for this user.</p>
                </div>
            @endif
        @endif
    </div>
@endsection
