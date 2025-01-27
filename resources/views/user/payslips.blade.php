@extends('user.sidebar')
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600&display=swap" rel="stylesheet">


@section('content')
    <style>
     
    body{
        font-family: 'Open Sans', sans-serif;
    }

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
        .custom-btn-white {
        background-color: white !important; 
        color: #5271ff !important; 
        border: 2px solid #5271ff !important; 
        font-weight: 600; 
        padding: 0.5rem 1.5rem;
        
        transition: all 0.3s ease; 
    }

    .custom-btn-white:hover {
        background-color: #5271ff !important;  
        color: white !important; 
    }
    </style>

<div class="containe-fluid">
    <h1 class="mb-4 text-left" style="color: #575b5b;">PaySlips</h1> 
        @if (isset($noDataMessage))
            <div class="alert alert-warning">
                {{ $noDataMessage }}
            </div>
        @else
            @if (isset($dateRanges) && count($dateRanges) > 0)
            <table class="table">
                <thead class="text-nowrap" style="color: #575b5b;" >
                    <tr >
                        <th>Week Range</th>
                        <th>Hours</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($dateRanges as $range)

                        <tr>
                            <td>{{ $range['start'] }} - {{ $range['end'] }}</td>
                            <td>
                                @if ($range['status'] === 'pending')
                                    Pending
                                @else
                                    {{ $range['hours'] ?? 0 }} hrs
                                @endif
                            </td>
                            <td class="text-end">
                                @if ($range['status'] === 'pending')
                                    Your timesheet is still pending, please contact your Company
                                @else
                                    <a href="{{ route('user.payslipsPdf', ['start' => $range['start'], 'end' => $range['end']]) }}" class="btn btn-sm custom-btn-white" target="_blank">
                                        <i class="fas fa-file-alt"></i> View Payslip
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">No payslips available</td>
                        </tr>
                    @endforelse
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