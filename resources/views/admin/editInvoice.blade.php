@extends('admin.sidebar')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">

@section('content')
    <div class="container-fluid">
        <h2 class="text-center mb-4">Update Invoice</h2>

        <form action="{{ route('admin.invoice.update', $invoice->id) }}" id="invoiceForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

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
   <div class="mb-2">
       <label for="invoiceNumber" class="form-label">Invoice Number</label>
       <input type="text" class="form-control" id="invoiceNumber" name="invoice_number" value="{{ $invoice->invoice_number }}" required>
   </div>

   <!-- Charges Section -->
   <h5>Charges</h5>
   <div id="charges">
       @foreach ($invoice->charge_names as $index => $charge_name)
           <div class="row">
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
    <!-- Container for additional charges -->
    <div id="additionalChargesContainer"></div>

    <!-- Button to add more charges -->
    <div class="">
        <button type="button" id="addChargeButton" class="btn btn-outline-primary">
            <i class="bi bi-plus-lg"></i> Add Charge
        </button>
    </div>


   <div class="col-md-6">
                        <label for="totalChargeRCs" class="form-label">Total Charge for RCs</label>
                        <input type="number" class="form-control" id="totalChargeRCs" name="total_charge_rcs" step="0.01" required value="{{$invoice->total_charge}}">
                    
                    </div>
<div class="col-md-6">
    <label for="totalTransferredRCs" class="form-label">Total Transferred to RCs</label>
                        <input type="number" class="form-control" id="totalTransferredRCs" name="total_transferred_rcs" step="0.01" value="{{$invoice->total_transferred}}" required>
</div>

<div class="col-md-6">
    <label for="previousCredits" class="form-label">Previous Credits</label>
    <input type="number" class="form-control" id="previousCredits" name="previous_credits" step="0.01"  value="{{$invoice->previous_credits}}"required>
</div>
        
<!-- Image Upload Section -->
                    <div class="col-md-6">
                        <label for="invoiceImages" class="form-label">Upload Invoice Images</label>
                        <input type="file" class="form-control d-none" id="invoiceImages" name="invoice_images[]" accept="image/*" multiple>
                        <button type="button" id="addImageButton" class="btn btn-outline-primary" style="font-size: 24px;">
                            <i class="bi bi-plus-lg"></i>
                        </button>
                    </div>

                    <!-- Image Preview Container -->
                    <div class="mb-4" id="imagePreviewContainer" style="display: flex; flex-wrap: wrap; gap: 10px;">
                        @foreach ($invoice->image_paths as $index => $path)
                            <div class="image-preview" style="position: relative; width: 100px; height: 100px; margin-bottom: 10px;">
                                <img src="{{ asset('storage/' . $path) }}" alt="Invoice Image" style="width: 100%; height: 100%; object-fit: cover;">
                                <button type="button" class="btn btn-danger btn-sm remove-image" data-index="{{ $index }}" style="position: absolute; top: 0; right: 0; background-color: red; color: white; border: none; padding: 5px;">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>
                            <input type="hidden" name="existing_images[]" value="{{ $path }}">
                        @endforeach
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


        // Add Image Button
        document.getElementById('addImageButton').addEventListener('click', function() {
            document.getElementById('invoiceImages').click();
        });

        // Handle file selection and preview
        document.addEventListener('DOMContentLoaded', function() {
    // Add event listener for removing existing images
    document.querySelectorAll('.remove-image').forEach(button => {
        button.addEventListener('click', function() {
            const container = this.closest('.image-preview'); // The image container
            const index = this.getAttribute('data-index'); // Get the index of the image to be removed
            const existingImagesInputs = document.querySelectorAll('input[name="existing_images[]"]');

            // Remove the image preview container from the DOM
            container.remove();

            // Loop through the existing image inputs and remove the corresponding one
            existingImagesInputs.forEach((input, idx) => {
                if (idx == index) {
                    input.remove(); // Remove the hidden input that corresponds to the image
                }
            });
        });
    });

    // Handle file selection and image preview
    document.getElementById('invoiceImages').addEventListener('change', function(event) {
        const files = event.target.files;
        const imagePreviewContainer = document.getElementById('imagePreviewContainer');

        // Loop through each selected file and create an image preview
        Array.from(files).forEach(file => {
            const reader = new FileReader();

            reader.onload = function(e) {
                // Create the image preview container
                const imageContainer = document.createElement('div');
                imageContainer.classList.add('image-preview');
                imageContainer.style.position = 'relative';
                imageContainer.style.width = '100px';
                imageContainer.style.height = '100px';
                imageContainer.style.marginBottom = '10px';

                // Create and set the image element
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.width = '100%';
                img.style.height = '100%';
                img.style.objectFit = 'cover';

                // Create and set the remove button
                const removeButton = document.createElement('button');
                removeButton.classList.add('btn', 'btn-danger', 'btn-sm', 'remove-image');
                removeButton.style.position = 'absolute';
                removeButton.style.top = '0';
                removeButton.style.right = '0';
                removeButton.style.backgroundColor = 'red';
                removeButton.style.color = 'white';
                removeButton.style.border = 'none';
                removeButton.style.padding = '5px';
                removeButton.innerHTML = '<i class="bi bi-x-lg"></i>';

                // Add click event listener to the remove button
                removeButton.addEventListener('click', function() {
                    // Remove the image from the preview container
                    imagePreviewContainer.removeChild(imageContainer);
                });

                // Append the image and the remove button to the image container
                imageContainer.appendChild(img);
                imageContainer.appendChild(removeButton);

                // Append the image container to the preview container
                imagePreviewContainer.appendChild(imageContainer);
            };

            // Read the file as a data URL
            reader.readAsDataURL(file);
        });
    });

    // Handle the add image button click to trigger the hidden file input
    document.getElementById('addImageButton').addEventListener('click', function() {
        document.getElementById('invoiceImages').click();
    });
});


        // Update the email field when a user is selected
        document.getElementById('invoiceFor').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            document.getElementById('email').value = selectedOption.getAttribute('data-email');
            document.getElementById('userId').value = selectedOption.getAttribute('data-id');
        });
    </script>
@endsection
