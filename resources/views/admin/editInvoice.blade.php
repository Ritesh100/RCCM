@extends('admin.sidebar')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">

@section('content')
    <div class="container-fluid">
        <h2 class="text-center mb-4">Create New Invoice</h2>

    <form action="{{ route('admin.invoice.update', $invoice->id) }}" id="invoiceForm" enctype="multipart/form-data">
        @csrf
        @method('PUT') <!-- This line is important to indicate the method -->


        <!-- Invoice for Week -->
        <div class="p-4 shadow rounded bg-light mx-auto">
            <div class="row g-3 align-items-center">
                <h5>Invoice For Week</h5>

                <!-- Week Start and End -->
                <div class="col-md-6">
                    <label for="week_start" class="form-label">Select Week Start:</label>
                    <input type="date" name="week_start" id="week_start" class="form-control" value="{{ substr($invoice->week_range, 0, 10) }}" required>
                </div>
                <div class="col-md-6">
                    <label for="week_end" class="form-label">Select Week End:</label>
                    <input type="date" name="week_end" id="week_end" class="form-control" value="{{ substr($invoice->week_range, -10) }}" required>
                </div>

                <!-- Invoice For -->
                <h5>Invoice For</h5>
                <div class="col-md-6">
                    <label for="invoice_for" class="form-label">Select User</label>
                    <select name="invoice_for" id="invoiceFor" class="form-select" required>
                        <option value="" disabled>Select a user</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->name }}" data-id="{{ $user->id }}" data-email="{{ $user->email }}" {{ $invoice->invoice_for == $user->name ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                    <input type="hidden" name="user_id" id="userId" value="{{ $invoice->user_id }}">
                </div>

                <!-- Email -->
                <div class="col-md-6">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ $invoice->email }}" required readonly>
                </div>

                <!-- Invoice From -->
                <h5>Invoice From</h5>
                <div class="col-md-4">
                    <label for="invoiceFrom" class="form-label">Invoice From</label>
                    <input type="text" class="form-control" id="invoiceFrom" name="invoice_from" value="{{ $admin->userName }}" required readonly>
                </div>

                <!-- Invoice Address From -->
                <div class="col-md-4">
                    <label for="invoiceAddressFrom" class="form-label">Invoice Address From</label>
                    <input type="text" class="form-control" id="invoiceAddressFrom" name="invoice_address_from" value="{{ $admin->address }}" required readonly>
                </div>

                <!-- Contact Email -->
                <div class="col-md-4">
                    <label for="contactEmail" class="form-label">Contact Email</label>
                    <input type="email" class="form-control" id="contactEmail" name="contact_email" value="{{ $admin->userEmail }}" required readonly>
                </div>

                <!-- Invoice Number -->
                <div class="mb-4">
                    <label for="invoiceNumber" class="form-label">Invoice Number</label>
                    <input type="text" class="form-control" id="invoiceNumber" name="invoice_number" value="{{ $invoice->invoice_number }}" required>
                </div>

                <!-- Charges Section -->
                <h5>Charges</h5>
                <div id="charges">
                    @foreach ($invoice->charge_names as $index => $charge_name)
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label for="charge_{{ $index }}_name" class="form-label">Charge Name</label>
                                <input type="text" class="form-control" id="charge_{{ $index }}_name" name="charges[{{ $index }}][name]" value="{{ $charge_name }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="charge_{{ $index }}_total" class="form-label">Charge Total</label>
                                <input type="number" class="form-control" id="charge_{{ $index }}_total" name="charges[{{ $index }}][total]" value="{{ $invoice->charge_totals[$index] }}" step="0.01" required>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Add additional fields for Total Charge, Previous Credits, etc. -->
                <!-- Image Upload Section -->
                <div>
                    <label for="invoice_images" class="form-label">Upload Invoice Images</label>
                    @foreach ($invoice->image_paths as $path)
                        <img src="{{ asset('storage/' . $path) }}" alt="Invoice Image" width="100">
                    @endforeach
                    <input type="file" class="form-control" id="invoice_images" name="invoice_images[]" multiple>
                </div>

                <!-- Submit Button -->
                <div class="mb-4">
                    <button type="submit" class="btn btn-success">Update Invoice</button>
                </div>

            </div>
        </div>
    </form>


        
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Initialize the index for additional charges
        let chargeIndex = 1;

// Event listener for the "Add Charge" button
document.getElementById('addChargeButton').addEventListener('click', function() {
    // Create a new row for the charge fields
    const newChargeRow = document.createElement('div');
    newChargeRow.classList.add('row', 'mb-3', 'align-items-center');

    // Charge Name Column
    const chargeNameCol = document.createElement('div');
    chargeNameCol.classList.add('col-md-6');
    const chargeNameLabel = document.createElement('label');
    chargeNameLabel.classList.add('form-label');
    chargeNameLabel.innerText = `Charge Name ${chargeIndex + 1}`;
    const chargeNameInput = document.createElement('input');
    chargeNameInput.type = 'text';
    chargeNameInput.classList.add('form-control');
    chargeNameInput.name = `charges[${chargeIndex}][name]`;
    chargeNameInput.required = true;
    chargeNameCol.appendChild(chargeNameLabel);
    chargeNameCol.appendChild(chargeNameInput);

    // Charge Total Column
    const chargeTotalCol = document.createElement('div');
    chargeTotalCol.classList.add('col-md-6');
    const chargeTotalLabel = document.createElement('label');
    chargeTotalLabel.classList.add('form-label');
    chargeTotalLabel.innerText = `Charge Total ${chargeIndex + 1}`;
    const chargeTotalInput = document.createElement('input');
    chargeTotalInput.type = 'number';
    chargeTotalInput.classList.add('form-control');
    chargeTotalInput.name = `charges[${chargeIndex}][total]`;
    chargeTotalInput.step = '0.01';
    chargeTotalInput.required = true;
    chargeTotalCol.appendChild(chargeTotalLabel);
    chargeTotalCol.appendChild(chargeTotalInput);

    // Remove Button Column
    const removeCol = document.createElement('div');
    removeCol.classList.add('col-md-12', 'd-flex', 'justify-content-end', 'align-items-center', 'mt-2'); // Added margin-top
    const removeButton = document.createElement('button');
    removeButton.type = 'button';
    removeButton.classList.add('btn', 'btn-danger', 'remove-charge-button', 'ms-2'); // Added margin-start for spacing
    removeButton.innerHTML = '<i class="bi bi-x-lg"></i>';
    removeButton.onclick = function() {
        newChargeRow.remove();
    };
    removeCol.appendChild(removeButton);

    // Append the new columns to the charge row
    newChargeRow.appendChild(chargeNameCol);
    newChargeRow.appendChild(chargeTotalCol);
    newChargeRow.appendChild(removeCol);

    // Append the new charge row to the container
    document.getElementById('additionalChargesContainer').appendChild(newChargeRow);

    // Increment the index for the next charge
    chargeIndex++;
});

        // Handle image upload preview and management
        document.getElementById('addImageButton').addEventListener('click', function() {
            // Trigger the hidden file input when + icon is clicked
            document.getElementById('invoiceImages').click();
        });

        document.getElementById('invoiceImages').addEventListener('change', function(event) {
            const files = event.target.files;
            const imagePreviewContainer = document.getElementById('imagePreviewContainer');

            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const reader = new FileReader();
                reader.onload = function(e) {
                    const imageContainer = document.createElement('div');
                    imageContainer.style.width = '100px';
                    imageContainer.style.height = '100px';
                    imageContainer.style.position = 'relative';
                    imageContainer.style.display = 'inline-block';

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.width = '100%';
                    img.style.height = '100%';
                    img.style.objectFit = 'cover';

                    const removeButton = document.createElement('button');
                    removeButton.innerHTML = 'X';
                    removeButton.style.position = 'absolute';
                    removeButton.style.top = '0';
                    removeButton.style.right = '0';
                    removeButton.style.background = 'red';
                    removeButton.style.color = 'white';
                    removeButton.style.border = 'none';
                    removeButton.style.cursor = 'pointer';

                    removeButton.onclick = function() {
                        imagePreviewContainer.removeChild(imageContainer);
                    };

                    imageContainer.appendChild(img);
                    imageContainer.appendChild(removeButton);
                    imagePreviewContainer.appendChild(imageContainer);
                };
                reader.readAsDataURL(file);
            }
        });

        // Update the email field when a user is selected
        document.getElementById('invoiceFor').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const email = selectedOption.getAttribute('data-email');
            const userId = selectedOption.getAttribute('data-id'); // Get the selected user's ID
            const userName = selectedOption.value; // Get the selected user's name

            // Update the email input field
            document.getElementById('email').value = email;

            // Update the hidden user_id field with the selected ID
            document.getElementById('userId').value = userId;

            // Update the form action URL with the user ID if needed
            const form = document.getElementById('invoiceForm');
            form.action = `/admin/invoicePost/${userId}`;

            console.log(`User ID: ${userId}, User Name: ${userName}`);
        });
    </script>
@endsection
