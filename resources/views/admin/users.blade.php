@extends('admin.sidebar')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

@section('content')
<style>
    body{
        font-family: 'Josefin Sans', sans-serif;
    }
    .custom-btn-white {
        background-color: white !important; /* White background */
        color: #5271ff !important; /* Text color */
        border: 2px solid #5271ff !important; /* Border color matching text */
        font-weight: 600; /* Bold text */
        padding: 0.5rem 1rem; /* Adjust padding for better button size */
        border-radius: 30px; /* Rounded-pill shape */
        transition: all 0.3s ease-in-out; /* Smooth hover effect */
    }

    .custom-btn-white:hover {
        background-color: #5271ff !important; /* Blue background on hover */
        color: white !important; /* White text on hover */
    }
    .action-link {
        color: #5271ff !important;
        font-size: 16px;
        text-decoration: none;
    }

    .action-link:hover {
        text-decoration: underline;
    }
    
</style>
<div class="container-fluid"> <!-- Ensure full width with container-fluid -->
    <h1 class="mb-4 text-left" style="color: #575b5b;">RC  Management</h1>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

          <!-- Global Search form -->
          <div class="d-flex justify-content-start mt-4 mb-4">
            <form action="{{ route('admin.users') }}" method="GET" class="input-group" style="max-width: 600px;">
                <input type="text" name="search" class="form-control rounded-pill" 
                    placeholder="Search by user name" value="{{ $searchQuery }}">
                    <button type="submit" class="btn custom-btn-white rounded-pill ms-2 mb-2">Search</button>
                    <!-- Reset Button -->
                    <button type="button" class="btn custom-btn-white rounded-pill ms-2 mb-2" 
                            onClick="window.location.href='{{ route('admin.users') }}'">Reset</button>
            </form>
        </div>

        <!-- Create Company Button -->
        <div class="d-flex mb-4">
            <a href="{{ route('admin.users.create') }}" class="btn custom-btn-white">
                <i class="fas fa-plus"></i> Create RC
            </a>
        </div>

        <!-- Company List Table -->
        <div class="table-responsive shadow-lg mt-4">
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
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="action-link me-3">
                                    <i class="fas fa-edit"></i> Edit
                                </a>

                                <!-- Delete Form -->
                                <a href="#" class="action-link me-3" onclick="event.preventDefault();
                                    if(confirm('Are you sure you want to delete this User?')){
                                    document.getElementById('delete-user-{{ $user->id}}')}.submit();                                
                            }">Delete</a>

                            <form id="delete-user-{{ $user->id }}" action="{{ route('admin.users.delete',$user->id)}}" method="POST" style="display: :none;">
                                @csrf
                                @method('DELETE')
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

