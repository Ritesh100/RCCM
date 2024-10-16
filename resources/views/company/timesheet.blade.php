@extends('company.sidebar')

@section('content')
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
