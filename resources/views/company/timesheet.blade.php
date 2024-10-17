@extends('company.sidebar')

@section('content')
<style>
    /* Basic styling for the form and table */
    form {
        width: 100%;
        margin-top: 20px;
    }

    label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }

    input,
    select {
        margin-bottom: 15px;
        padding: 5px;
        width: 100%;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th,
    td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: center;
    }

    th {
        background-color: #f2f2f2;
        font-weight: bold;
    }

    hr {
        margin: 20px 0;
    }

    .timesheet-container {
        margin: 0 auto;
    }

    .pagination {
        display: flex;
        justify-content: center;
        list-style-type: none;
        padding: 0;
    }

    .pagination li {
        margin: 0 5px;
    }

    .pagination a,
    .pagination span {
        display: block;
        padding: 10px 15px;
        border: 1px solid #007bff;
        /* Bootstrap primary color */
        color: #007bff;
        text-decoration: none;
        border-radius: 5px;
        transition: background-color 0.3s;
    }

    .pagination a:hover {
        background-color: #007bff;
        /* Change to Bootstrap primary color on hover */
        color: white;
    }

    .pagination .active span {
        background-color: #007bff;
        color: white;
        border: 1px solid #007bff;
    }

    .pagination .disabled span {
        color: #6c757d;
        /* Bootstrap secondary color for disabled */
        border: 1px solid #6c757d;
    }

    .pagination .ellipsis {
        padding: 10px 15px;
    }
</style>
    <h3>Timesheet</h3>

    <table border="1">
        <thead>
            <tr>
                <th>S.N.</th>
                <th>Day</th>
                <th>Cost Center</th>
                <th>Date</th>
                <th>Start Time</th>
                <th>Close Time</th>
                <th>Break Start</th>
                <th>Break End</th>
                <th>Timezone</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $index => $timesheet)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $timesheet->day }}</td>
                    <td>{{ $timesheet->cost_center }}</td>
                    <td>{{ $timesheet->date }}</td>
                    <td>{{ $timesheet->start_time }}</td>
                    <td>{{ $timesheet->close_time }}</td>
                    <td>{{ $timesheet->break_start }}</td>
                    <td>{{ $timesheet->break_end }}</td>
                    <td>{{ $timesheet->timezone }}</td>
                    <td>{{ $timesheet->status }}</td>
                    <td>
                        @if ($timesheet->status == 'pending')
                        <form action="{{ route('timesheet.updateStatus', $timesheet->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" onclick="return confirm('Are you sure you want to approve this timesheet?');">
                                Approve
                            </button>
                        </form>
                    @else
                        Approved
                    @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="pagination">
        {{ $users->links('pagination::bootstrap-4') }}
    </div>
@endsection
