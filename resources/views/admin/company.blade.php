@extends('admin.sidebar')

@section('content')
    <div class="container mt-4">
        <h1 class="mb-4 text-center">RCC Partner</h1>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <!-- Create Company Button -->
        <div class="d-flex justify-content-end mb-4">
            <a href="{{ route('admin.company.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create RCC Partner
            </a>
        </div>

        <!-- Company List Table -->
        <div class="table-responsive shadow-lg">
            <table class="table table-hover table-striped table-borderless align-middle">
                <thead class="bg-primary text-white">
                    <tr>
                        <th class="text-center">#</th>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Contact</th>

                        <th>Email</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($companies as $index => $company)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="font-weight-bold">{{ $company->name }}</td>
                            <td class="font-weight-bold">{{ $company->address }}</td>
                            <td class="font-weight-bold">{{ $company->contact }}</td>

                            <td>{{ $company->email }}</td>
                            <td class="text-center">
                                <!-- Edit Button -->
                                <a href="{{ route('admin.company.edit', $company->id) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i> Edit
                                </a>

                                <!-- Delete Form -->
                                <form action="{{ route('admin.company.delete', $company->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this company?');">
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
