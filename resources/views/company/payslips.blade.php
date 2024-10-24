@extends('company.sidebar')

@section('content')
    <style>
        a {
            text-decoration: none;
        }

        .search-box {
            margin-bottom: 20px;
        }

        .employee-section {
            margin-bottom: 30px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
        }

        .employee-header {
            background-color: #f8f9fa;
            padding: 10px 15px;
            border-bottom: 1px solid #dee2e6;
        }

        .employee-content {
            padding: 15px;
        }

        .no-data {
            padding: 20px;
            text-align: center;
            color: #6c757d;
        }
    </style>

    <div class="container">
   

        @if (empty($userPayslips))
            <div class="alert alert-warning">
                No payslip data available for any employees.
            </div>
        @else
            @forelse($userPayslips as $userData)
                <div class="employee-section">
                    <div class="employee-header">
                        <h4 class="m-0">{{ $userData['user']->name }}</h4>
                        <small class="text-muted">{{ $userData['user']->email }}</small>
                    </div>
                    <div class="employee-content">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Week Range</th>
                                    <th>Hours Worked</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($userData['dateRanges'] as $range)
                                    <tr>
                                        <td>{{ $range['start'] }} - {{ $range['end'] }}</td>
                                        <td>{{ $range['hours'] }} hrs</td>
                                        <td class="text-end">
                                            <a href="{{ route('company.generatepayslip', [
                                                'userId' => $userData['user']->id,
                                                'weekRange' => $range['start'] . ' - ' . $range['end'],
                                            ]) }}"
                                                class="btn btn-sm btn-primary" target="_blank">
                                                <i class="fas fa-file-alt"></i> View Payslip
                                            </a>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @empty
                <div class="no-data">
                    <h3>No Results Found</h3>
                    <p>No employees match your search criteria.</p>
                </div>
            @endforelse
        @endif
    </div>
@endsection
