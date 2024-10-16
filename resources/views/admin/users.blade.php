@extends('admin.sidebar')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    .table-responsive {
        margin-left: 0;
        margin-right: 0;
        width: 70vw;
    }
    
    .table {
        width: 100% !important; /* Force full width */
    }
</style>
@section('content')
<div class="container-fluid"> <!-- Ensure full width with container-fluid -->
    <h1 class="mb-4 text-center">RC  Management</h1>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <!-- Create Company Button -->
        <div class="d-flex mb-4">
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create RC
            </a>
        </div>

        <!-- Company List Table -->
        <div class="table-responsive shadow-lg">
            <table class="table table-hover table-striped table-borderless align-middle w-100"> <!-- Add w-100 for full width -->
                <thead class=" text-black">
                    <tr>
                        <th class="text-center">#</th>
                        <th>RC Name</th>
                        <th>Address</th>
                        <th>Contact</th>
                        <th>Email</th>
                        <th>Reporting To</th>
                        <th>Hourly Rate</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $index => $user)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="font-weight-bold">{{ $user->name }}</td>
                            <td class="font-weight-bold">{{ $user->address }}</td>
                            <td class="font-weight-bold">{{ $user->contact }}</td>
                            <td>{{ $user->email }}</td>
                            <td class="font-weight-bold">{{ $user->reportingTo }}</td>
                            <td class="font-weight-bold">{{ $user->hrlyRate }}</td>
                            <td class="text-center">
                                <!-- Edit Button -->
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i> Edit
                                </a>

                                <!-- Delete Form -->
                                <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this User?');">
                                        <i class="fas fa-trash-alt"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

