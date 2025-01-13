@extends('admin.sidebar')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-XLZI48k5a5Y7sEq6Hp7MNJ+UDEEGPzPHTxSAIDzOeXf4mrn4QU7pkX9q3GJkBq8v" crossorigin="anonymous">


    <style>
        .company-section {
            margin-bottom: 30px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .company-header {
            background-color: #f8f9fa;
            padding: 15px;
            border-bottom: 1px solid #dee2e6;
        }

        .company-info {
            color: #6c757d;
            margin-top: 5px;
        }
      
  
       
    </style>

    <div class="containe-fluid">
        <h1 class="mb-4 text-center">Timesheet Management</h1>

        <!-- Global Search form -->
        {{-- <div class="d-flex justify-content-center mt-4 mb-4">
            <form action="{{ route('admin.timesheets') }}" method="GET" class="input-group" style="max-width: 600px;">
                <input type="text" name="search" class="form-control rounded-pill" 
                    placeholder="Search by company name or user name" value="{{ $searchQuery }}">
                <button type="submit" class="btn btn-primary rounded-pill ms-2">Search</button>
                <button type="button" class="btn btn-secondary rounded-pill ms-2"  
                        onClick="window.location.href='{{ route('admin.timesheets') }}'">Reset</button>
            </form>
        </div> --}}

        <!-- Filter Form for Specific Criteria -->        
        <form action="{{ route('admin.timesheets') }}" method="GET" class="input-group" style="max-width: 1000px;">

            <select name="company_name" class="form-select me-2 filter-select mb-2">
                <option value="">Select Company</option>
                @foreach($uniqueCompanies as $email => $name)
                    <option value="{{ $email }}" {{ request('company_name') == $email ? 'selected' : '' }}>
                        {{ $name }}
                    </option>
                @endforeach
            </select>

            <select name="username" class="form-select me-2 filter-select mb-2">
                <option value="">Select Username</option>
                @foreach($uniqueUsernames as $username)
                    <option value="{{ $username }}" {{ request('username') == $username ? 'selected' : '' }}>{{ $username }}</option>
                @endforeach
            </select>
        
            {{-- <select name="day" class="form-select me-2 filter-select mb-2">
                <option value="">Select Day</option>
                @foreach($uniqueDays as $day)
                    <option value="{{ $day }}" {{ request('day') == $day ? 'selected' : '' }}>{{ $day }}</option>
                @endforeach
            </select> --}}
        
            <select name="cost_center" class="form-select me-2 filter-select mb-2">
                <option value="">Select Cost Center</option>
                @foreach($uniqueCostCenters as $costCenter)
                    <option value="{{ $costCenter }}" {{ request('cost_center') == $costCenter ? 'selected' : '' }}>{{ $costCenter }}</option>
                @endforeach
            </select>

            <select name="status" class="form-select me-2 filter-select mb-2">
                <option value="">Select Status</option>
                @foreach($uniqueStatuses as $status)
                    <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                @endforeach
            </select> 

            <select name="date" class="form-select me-2 filter-select mb-2">
                <option value="">Select Date</option>
                @foreach($uniqueDates as $date)
                    <option value="{{ $date }}" {{ request('date') == $date ? 'selected' : '' }}>{{ $date }}</option>
                @endforeach
            </select>
        
            <button type="submit" class="btn btn-primary rounded-pill ms-2 mb-2">Filter</button>
            <button type="button" class="btn btn-secondary rounded-pill ms-2 mb-2" onClick="window.location.href='{{ route('admin.timesheets') }}'">Reset</button>
        </form>

        @php
        $isFiltered = request()->has('username') || request()->has('company_name')  || request()->has('cost_center')  || request()->has('status')  || request()->has('date');
        @endphp
        

        
        <div class="export-buttons text-end justify-content-end g-1 text-nowrap mb-1 mt-3" >
            <a href="{{ route('export.timesheets.all') }}" 
                class="btn btn-success btn-sm ">
                <i class="fas fa-file-excel me-2"></i>Export All
            </a>
            <a href="{{ route('export.timesheets.approved') }}" 
                class="btn btn-primary btn-sm">
                <i class="fas fa-check-circle me-2"></i>Export Approved
            </a>
            <a href="{{ route('export.timesheets.pending') }}" 
                class="btn btn-warning btn-sm">
                <i class="fas fa-clock me-2"></i>Export Pending
            </a>
        </div>
       
       {{-- <! -- Pending Timesheets Section --> --}}
       @if (!$isFiltered)
       <div class="alert alert-info mt-4">
        Please select filters
       </div>
       @else
     
     
        @foreach($pendingTimesheets->groupBy('company_email') as $companyEmail => $companyTimesheets)
            <div class="company-section mb-4">
                <div class="company-header">
                    <h4>{{ $companyTimesheets->first()->company_name ?? 'Unknown Company' }}</h4>
                    <div class="company-info">
                        <i class="fas fa-envelope"></i> {{ $companyEmail }}
                    </div>
                </div>
        
                <h5 class="p-2 mt-2">Pending Timesheets</h5>
                <form action="{{ route('admin.timesheet.bulkUpdate') }}" method="POST" class="bulk-update-form mb-2">
                    @csrf
                    @method('PUT')
                    <div class="d-flex gap-2 align-items-center">
                        <input type="hidden" name="timesheet_ids" class="selected-ids">
                        <select name="status" class="form-select form-select-sm m-2" style="width: auto;">
                            <option value="">Bulk Update</option>
                            <option value="pending">Pending</option>
                            <option value="approved">Approve</option>
                            <option value="delete">Delete</option>
                        </select>
                        <button type="submit" class="btn btn-success btn-sm bulk-update-btn" disabled
                                onclick="return confirm('Are you sure you want to update all selected records?');">
                            Update Selected (<span class="selected-count">0</span>)
                        </button>
                    </div>
                </form>
        
                <div class="table-responsive shadow-lg">
                    <table class="table table-striped table-hover table-bordered align-middle table-sm" style="font-size: 0.75em;">
                        <thead class="text-black">
                                <th>
                                    <input type="checkbox" class="select-all-checkbox"
                                           data-table-type="pending"
                                           data-company-email="{{ $companyEmail }}">
                                </th>
                                <th>S.N.</th>
                                <th>User Name</th>
                                <th>Day</th>
                                <th>Email</th>
                                <th>Cost Center</th>
                                <th>Currency</th>
                                <th>Date</th>
                                <th>Start Time</th>
                                <th>Close Time</th>
                                <th>Break Start</th>
                                <th>Break End</th>
                                <th>Timezone</th>
                                <th>Status</th>
                                <th>Work Time</th>
                                <th >Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($companyTimesheets as $index => $timesheet)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="row-checkbox"
                                               data-table-type="pending"
                                               data-company-email="{{ $companyEmail }}"
                                               value="{{ $timesheet->id }}">
                                    </td>
                                    <td>{{ $pendingTimesheets->firstItem() + $index }}</td> <!-- This will display the correct number across paginated pages -->
                                    <td>{{ $timesheet->user_name }}</td>
                                    <td>{{ $timesheet->day }}</td>
                                    <td>{{ $timesheet->user_email }}</td>
                                    <td>{{ $timesheet->cost_center }}</td>
                                    <td>{{ $timesheet->currency }}</td>
                                    <td>{{ $timesheet->date }}</td>
                                    <td>{{ $timesheet->start_time }}</td>
                                    <td>{{ $timesheet->close_time }}</td>
                                    <td>{{ $timesheet->break_start }}</td>
                                    <td>{{ $timesheet->break_end }}</td>
                                    <td>{{ $timesheet->timezone }}</td>
                                    <td>
                                        <span class="badge 
                                            {{ $timesheet->status == 'approved' ? 'bg-success' : '' }}
                                            {{ $timesheet->status == 'pending' ? 'bg-warning text-dark' : '' }}
                                            {{ $timesheet->status == 'deleted' ? 'bg-danger' : '' }}">
                                            {{ ucfirst($timesheet->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $timesheet->work_time }}</td>
                                    <td class="text-nowrap">
                                        <form action="{{ route('admin.timesheet.updateStatus', $timesheet->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <select name="status" class="form-select form-select-sm mb-1" style="font-size: 0.90em;">
                                                <option value="pending" {{ $timesheet->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="approved" {{ $timesheet->status == 'approved' ? 'selected' : '' }}>Approve</option>
                                                <option value="deleted">Delete</option>
                                            </select>
                                            <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Are you sure?');" style="font-size: 0.90em;">
                                                Update
                                            </button>
                                        </form>
                                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#editModal" onclick="openEditModal({{ json_encode($timesheet) }})" style="font-size: 0.90em;">
                                            Edit
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="p-2">
                    {{ $pendingTimesheets->appends(request()->except('pending_page'))->links() }}
                </div>
                </div>
            </div>
        @endforeach
      
        
        
        
        <!-- Approved Timesheets Section -->
        
        @foreach($approvedTimesheets->groupBy('company_email') as $companyEmail => $companyTimesheets)
            <div class="company-section mb-4">
                <div class="company-header">
                    <h4>{{ $companyTimesheets->first()->company_name ?? 'Unknown Company' }}</h4>
                    <div class="company-info">
                        <i class="fas fa-envelope"></i> {{ $companyEmail }}
                    </div>
                </div>
        
                <h5 class="p-2 mt-2">Approved Timesheets</h5>
                <form action="{{ route('admin.timesheet.bulkUpdate') }}" method="POST" class="bulk-update-form mb-2">
                    @csrf
                    @method('PUT')
                    <div class="d-flex gap-2 align-items-center">
                        <input type="hidden" name="timesheet_ids" class="selected-ids">
                        <select name="status" class="form-select form-select-sm m-2" style="width: auto;">
                            <option value="">Bulk Update</option>
                            <option value="pending">Pending</option>
                            <option value="approved">Approve</option>
                            <option value="delete">Delete</option>
                        </select>
                        <button type="submit" class="btn btn-success btn-sm bulk-update-btn" disabled
                                onclick="return confirm('Are you sure you want to update all selected records?');">
                            Update Selected (<span class="selected-count">0</span>)
                        </button>
                    </div>
                </form>
        
                <div class="table-responsive shadow-lg">
                    <table class="table table-striped table-hover table-bordered align-middle table-sm" style="font-size: 0.75em;">
                        <thead class="text-black">
                            <tr>
                                <th>
                                    <input type="checkbox" class="select-all-checkbox"
                                           data-table-type="approved"
                                           data-company-email="{{ $companyEmail }}">
                                </th>
                                <th>S.N.</th>
                                <th>User Name</th>
                                <th>Day</th>
                                <th>Email</th>
                                <th>Cost Center</th>
                                <th>Currency</th>
                                <th>Date</th>
                                <th>Start Time</th>
                                <th>Close Time</th>
                                <th>Break Start</th>
                                <th>Break End</th>
                                <th>Timezone</th>
                                <th>Status</th>
                                <th>Work Time</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($companyTimesheets as $index => $timesheet)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="row-checkbox"
                                               data-table-type="approved"
                                               data-company-email="{{ $companyEmail }}"
                                               value="{{ $timesheet->id }}">
                                    </td>
                                    <td>{{ $approvedTimesheets->firstItem() + $index }}</td> <!-- This will display the correct number across paginated pages -->
                                    <td>{{ $timesheet->user_name }}</td>
                                    <td>{{ $timesheet->day }}</td>
                                    <td>{{ $timesheet->user_email }}</td>
                                    <td>{{ $timesheet->cost_center }}</td>
                                    <td>{{ $timesheet->currency }}</td>
                                    <td>{{ $timesheet->date }}</td>
                                    <td>{{ $timesheet->start_time }}</td>
                                    <td>{{ $timesheet->close_time }}</td>
                                    <td>{{ $timesheet->break_start }}</td>
                                    <td>{{ $timesheet->break_end }}</td>
                                    <td>{{ $timesheet->timezone }}</td>
                                    <td>
                                        <span class="badge 
                                            {{ $timesheet->status == 'approved' ? 'bg-success' : '' }}
                                            {{ $timesheet->status == 'pending' ? 'bg-warning text-dark' : '' }}
                                            {{ $timesheet->status == 'deleted' ? 'bg-danger' : '' }}">
                                            {{ ucfirst($timesheet->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $timesheet->work_time }}</td>
                                    <td class="text-nowrap">
                                        <form action="{{ route('admin.timesheet.updateStatus', $timesheet->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <select name="status" class="form-select form-select-sm mb-2"  style="font-size: 0.90em;" >
                                                <option value="pending" {{ $timesheet->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="approved" {{ $timesheet->status == 'approved' ? 'selected' : '' }}>Approve</option>
                                                <option value="deleted">Delete</option>
                                            </select>
                                            <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Are you sure?');"  style="font-size: 0.90em;">
                                                Update
                                            </button>
                                        </form>
                                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#editModal" onclick="openEditModal({{ json_encode($timesheet) }})"  style="font-size: 0.90em;">
                                            Edit
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="p-2">
                        {{ $approvedTimesheets->appends(request()->except('approved_page'))->links() }}
                    </div>
                </div>
            </div>
        @endforeach
        @endif
    </div>
    

    

        <!-- Edit Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Timesheet</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editTimesheetForm" action="" method="POST">
                            @csrf
                            @method('PUT')

                            <input type="hidden" name="timesheet_id" id="timesheet_id">

                            <div class="mb-3">
                                <label for="editDay" class="form-label">Day</label>
                                <input type="text" class="form-control" id="editDay" name="day">
                            </div>
                            <div class="mb-3">
                                <label for="editEmail" class="form-label">User Email</label>
                                <input type="email" class="form-control" id="editEmail" name="user_email" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="editCostCenter" class="form-label">Cost Center</label>
                                <select name="cost_center" class="form-control" id="editCostCenter">
                                    <option value="hrs_worked">Hrs Worked</option>
                                    <option value="annual_leave">Annual Leave</option>
                                    <option value="sick_leave">Sick Leave</option>
                                    <option value="public_holiday">Public Holiday</option>
                                    <option value="unpaid_leave"> Unpaid Leave</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="editCurrency" class="form-label">Currency</label>
                                <select name="currency" class="form-control" id="editCurrency">
                                    <option value="NPR">Nepali Rupee (NPR)</option>
                                    <option value="USD">United States Dollar (USD)</option>
                                    <option value="EUR">Euro (EUR)</option>
                                    <option value="JPY">Japanese Yen (JPY)</option>
                                    <option value="GBP">British Pound Sterling (GBP)</option>
                                    <option value="AUD">Australian Dollar (AUD)</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="editDate" class="form-label">Date</label>
                                <input type="date" class="form-control" id="editDate" name="date">
                            </div>
                            <div class="mb-3">
                                <label for="editStartTime" class="form-label">Start Time</label>
                                <input type="time" class="form-control" id="editStartTime" name="start_time"
                                    oninput="calculateWorkTime()">
                            </div>
                            <div class="mb-3">
                                <label for="editCloseTime" class="form-label">Close Time</label>
                                <input type="time" class="form-control" id="editCloseTime" name="close_time"
                                    oninput="calculateWorkTime()">
                            </div>
                            <div class="mb-3">
                                <label for="editBreakStart" class="form-label">Break Start</label>
                                <input type="time" class="form-control" id="editBreakStart" name="break_start"
                                    oninput="calculateWorkTime()">
                            </div>
                            <div class="mb-3">
                                <label for="editBreakEnd" class="form-label">Break End</label>
                                <input type="time" class="form-control" id="editBreakEnd" name="break_end"
                                    oninput="calculateWorkTime()">
                            </div>
                            <div class="mb-3">
                                <label for="WorkTime" class="form-label">Work Time</label>
                                <input type="text" class="form-control" id="WorkTime" name="work_time" readonly>
                            </div>

                            <div class="mb-3">
                                <label for="editTimezone" class="form-label">Timezone</label>
                                <select name="timezone" id="editTimezone" class="form-control">
                                    <option value="Australia/Sydney">Australia/Sydney (AEST)</option>
                                    <option value="Australia/Melbourne">Australia/Melbourne (AEST)</option>
                                    <option value="Australia/Brisbane">Australia/Brisbane (AEST)</option>
                                    <option value="Australia/Perth">Australia/Perth (AWST)</option>
                                    <option value="Australia/Adelaide">Australia/Adelaide (ACST)</option>
                                    <option value="Australia/Darwin">Australia/Darwin (ACST)</option>
                                    <option value="Australia/Hobart">Australia/Hobart (AEST)</option>
                                    <option value="Australia/Broken_Hill">Australia/Broken Hill (ACST)</option>
                                    <option value="Australia/Lord_Howe">Australia/Lord Howe (LHST)</option>
                                </select>
                            </div>



                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js" integrity="sha384-Qh0JpDq/bbmfTHp+p7Iwhz5xEtOn23wPUZt4UPc0Iz5boKkktJ/E0i7K+Psm0T5F" crossorigin="anonymous"></script>
    <script>

//for bulk delete
function confirmBulkAction(form) {
    const status = form.querySelector('select[name="status"]').value;
    if (status === 'delete') {
        return confirm('Warning: This will permanently delete all selected records. Are you sure?');
    }
    return confirm('Are you sure you want to update selected records?');
}
        document.addEventListener('DOMContentLoaded', function() {
            // Handle "Select All" checkboxes
            const selectAllCheckboxes = document.querySelectorAll('.select-all-checkbox');        
            selectAllCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const tableType = this.dataset.tableType;
                    const companyEmail = this.dataset.companyEmail;
                    
                    // Find all row checkboxes in the same table and company
                    const rowCheckboxes = document.querySelectorAll(
                        `.row-checkbox[data-table-type="${tableType}"][data-company-email="${companyEmail}"]`
                    );
                    
                    // Set all matching checkboxes to the same state as the header checkbox
                    rowCheckboxes.forEach(rowCheckbox => {
                        rowCheckbox.checked = this.checked;
                    });
        
                    // Update bulk update form
                    updateBulkUpdateForm(tableType, companyEmail);
                });
            });
        
            // Handle individual row checkboxes
            const rowCheckboxes = document.querySelectorAll('.row-checkbox');
            
            rowCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const tableType = this.dataset.tableType;
                    const companyEmail = this.dataset.companyEmail;
                    
                    // Update "Select All" checkbox state
                    updateSelectAllCheckbox(tableType, companyEmail);
                    
                    // Update bulk update form
                    updateBulkUpdateForm(tableType, companyEmail);
                });
            });
        
            function updateSelectAllCheckbox(tableType, companyEmail) {
                const relatedCheckboxes = document.querySelectorAll(
                    `.row-checkbox[data-table-type="${tableType}"][data-company-email="${companyEmail}"]`
                );
                
                const selectAllCheckbox = document.querySelector(
                    `.select-all-checkbox[data-table-type="${tableType}"][data-company-email="${companyEmail}"]`
                );
                
                if (selectAllCheckbox) {
                    const allChecked = Array.from(relatedCheckboxes).every(cb => cb.checked);
                    selectAllCheckbox.checked = allChecked;
                }
            }
        
            function updateBulkUpdateForm(tableType, companyEmail) {
                // Find selected checkboxes for this table and company
                const selectedCheckboxes = document.querySelectorAll(
                    `.row-checkbox[data-table-type="${tableType}"][data-company-email="${companyEmail}"]:checked`
                );
                
                // Find the corresponding bulk update form
                const table = document.querySelector(
                    `.row-checkbox[data-table-type="${tableType}"][data-company-email="${companyEmail}"]`
                )?.closest('.table-responsive');
                
                if (table) {
                    const form = table.previousElementSibling;
                    if (form && form.classList.contains('bulk-update-form')) {
                        // Update selected IDs
                        const selectedIds = Array.from(selectedCheckboxes).map(cb => cb.value);
                        form.querySelector('.selected-ids').value = JSON.stringify(selectedIds);
                        
                        // Update counter and button state
                        const selectedCount = selectedCheckboxes.length;
                        form.querySelector('.selected-count').textContent = selectedCount;
                        form.querySelector('.bulk-update-btn').disabled = selectedCount === 0;
                    }
                }
            }
        });
     </script>
    <script>
         function openEditModal(timesheet) {
                // Populate the modal fields with the selected timesheet's data
                document.getElementById('timesheet_id').value = timesheet.id;
                document.getElementById('editDay').value = timesheet.day;
                document.getElementById('editEmail').value = timesheet.user_email;
                document.getElementById('editCostCenter').value = timesheet.cost_center;
                document.getElementById('editCurrency').value = timesheet.currency;
                document.getElementById('editDate').value = timesheet.date;
                document.getElementById('editStartTime').value = timesheet.start_time;
                document.getElementById('editCloseTime').value = timesheet.close_time;
                document.getElementById('editBreakStart').value = timesheet.break_start;
                document.getElementById('editBreakEnd').value = timesheet.break_end;

                // Set the timezone value
                document.getElementById('editTimezone').value = timesheet.timezone;

                // Set the calculated work time
                document.getElementById('WorkTime').value = timesheet.work_time; // Ensure this matches your field ID

                // Set the form action to the correct route
                document.getElementById('editTimesheetForm').action = `/timesheet/update/${timesheet.id}`;
            }

        function calculateWorkTime(startTime, closeTime, breakStart, breakEnd) {
            const start = new Date(`1970-01-01T${startTime}:00`);
            const close = new Date(`1970-01-01T${closeTime}:00`);
            const breakStartTime = breakStart ? new Date(`1970-01-01T${breakStart}:00`) : null;
            const breakEndTime = breakEnd ? new Date(`1970-01-01T${breakEnd}:00`) : null;

            let workTime = (close - start) / (1000 * 60 * 60); // hours
            if (breakStartTime && breakEndTime) {
                workTime -= (breakEndTime - breakStartTime) / (1000 * 60 * 60); // deduct break time
            }

            document.getElementById('work_time').value = workTime.toFixed(2) + ' hours';
        }

        document.getElementById('start_time').addEventListener('change', function () {
            const startTime = this.value;
            const closeTime = document.getElementById('close_time').value;
            const breakStart = document.getElementById('break_start').value;
            const breakEnd = document.getElementById('break_end').value;

            calculateWorkTime(startTime, closeTime, breakStart, breakEnd);
        });

        document.getElementById('close_time').addEventListener('change', function () {
            const closeTime = this.value;
            const startTime = document.getElementById('start_time').value;
            const breakStart = document.getElementById('break_start').value;
            const breakEnd = document.getElementById('break_end').value;

            calculateWorkTime(startTime, closeTime, breakStart, breakEnd);
        });

        document.getElementById('break_start').addEventListener('change', function () {
            const breakStart = this.value;
            const startTime = document.getElementById('start_time').value;
            const closeTime = document.getElementById('close_time').value;
            const breakEnd = document.getElementById('break_end').value;

            calculateWorkTime(startTime, closeTime, breakStart, breakEnd);
        });

        document.getElementById('break_end').addEventListener('change', function () {
            const breakEnd = this.value;
            const startTime = document.getElementById('start_time').value;
            const closeTime = document.getElementById('close_time').value;
            const breakStart = document.getElementById('break_start').value;

            calculateWorkTime(startTime, closeTime, breakStart, breakEnd);
        });
    </script>
@endsection
