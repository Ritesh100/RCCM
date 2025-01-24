@extends('admin.sidebar')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>

    body{

        font-family: 'Josefin Sans', sans-serif;
    }

    .btn-custom {
        background-color: white !important;
        color: #5271ff !important;
        border: 2px solid #5271ff !important;
        transition: all 0.3s ease;
    }

    .btn-custom:hover {
        background-color: #f8f9fa !important;
        color: #5271ff !important;
    }
.action-link {
    /* font-size: 16px; */
    /* font-weight: bold; */
    cursor: pointer;
    transition: color 0.3s ease;
    color: #5271ff !important;
        font-size: 16px;
        text-decoration: none;
}

.action-link:hover {
    text-decoration: underline;
}

.text-warning {
    color: #ffc107 !important; 
}

.text-danger {
    color: #dc3545 !important; 
}

</style>
@section('content')
    <div class="container-fluid"> <!-- Ensure full width with container-fluid -->
        <h1 class="mb-4 text-left" style="color: #575b5b;">RCC Partner</h1>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

          <!-- Global Search form -->
          <div class="d-flex justify-content-start mt-4 mb-4">
            <form action="{{ route('admin.company') }}" method="GET" class="input-group" style="max-width: 600px;">
                <input type="text" name="search" class="form-control rounded-pill" 
                    placeholder="Search by company name" value="{{ $searchQuery }}">
                    <button type="submit" class="btn btn-custom rounded-pill ms-2">Search</button>
                    <button type="button" class="btn btn-custom rounded-pill ms-2"  
                            onClick="window.location.href='{{ route('admin.company') }}'">Reset</button>
            </form>
        </div>
        <!-- Create Company Button -->
        <div class="d-flex mb-4">
            <a href="{{ route('admin.company.create') }}" class="btn btn-custom">
                <i class="fas fa-plus"></i> Create RCC Partner
            </a>
        </div>

        <!-- Company List Table -->
        <div class="table-responsive shadow-lg mt-4">
            <table class="table table-hover table-striped table-borderless align-middle w-100"> <!-- w-100 for full width -->
                <thead class=" text-black"> <!-- Add background color for the header -->
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
                                <a href="{{ route('admin.company.edit', $company->id) }}" class="action-link text-decoration-none me-3">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                
                                <!-- Delete Form -->
                                <form action="{{ route('admin.company.delete', $company->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-link border-0 bg-transparent text-decoration-none" onclick="return confirm('Are you sure you want to delete this company?');">
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
