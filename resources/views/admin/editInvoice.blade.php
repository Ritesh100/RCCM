@extends('admin.sidebar')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
<link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

@section('content')


<style>
    body{
        font-family: 'Josefin Sans', sans-serif;
    }
</style>

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

                    <div class="col-md-6">
                        <label for="status" class="form-label">Select Status</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="" disabled>Select Status</option>
                            <option value="Pending" {{ old('status', $invoice->status) == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Paid" {{ old('status', $invoice->status) == 'Paid' ? 'selected' : '' }}>Paid</option>
                        </select>
                        @error('status')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    


                    <!-- Invoice For -->
                    <h5>Invoice For</h5>
                    <div class="col-md-6">
                        <label for="invoice_for" class="form-label">Select Company</label>
                        <select name="invoice_for" id="invoiceFor" class="form-select" required>
                            <option value="" disabled>Select a  Company</option>
                            @foreach ($companies as $company)
                                <option value="{{ $company->name }}" data-id="{{ $company->id }}" data-email="{{ $company->email }}" {{ $invoice->invoice_for == $company->name ? 'selected' : '' }}>
                                    {{ $company->name }}
                                </option>
                            @endforeach
                        </select>
                        <input type="hidden" name="company_id" id="companyId" value="{{ $invoice->company_id }}">
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

  
   <div class="row g-2 align-items-center">
    <div class="col-md-6">
        <label for="currency" class="form-label">Select Currency</label>
        <select name="currency" id="currency" class="form-select" required>
            <option value="" disabled>Select Currency</option>
            <option value="AUD" {{ old('currency', $invoice->currency) == 'AUD' ? 'selected' : '' }}>Australia (AUD)</option>
            <option value="NPR" {{ old('currency', $invoice->currency) == 'NPR' ? 'selected' : '' }}>Nepal (NPR)</option>
            <option value="INR" {{ old('currency', $invoice->currency) == 'INR' ? 'selected' : '' }}>India (INR)</option>
            <option value="USD" {{ old('currency', $invoice->currency) == 'USD' ? 'selected' : '' }}>United States (USD)</option>
            <option value="CAD" {{ old('currency', $invoice->currency) == 'CAD' ? 'selected' : '' }}>Canada (CAD)</option>
        </select>
        @error('currency')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
     
            <!-- Invoice Number -->
            <div class="col-md-6">
                <label for="invoiceNumber" class="form-label">Invoice Number</label>
                <input type="text" class="form-control" id="invoiceNumber" name="invoice_number" value="{{ $invoice->invoice_number }}" required>
                @error('invoice_number')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
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

<div class="col-md-6">
    <label for="totalCredit" class="form-label">Total Credit</label>
    <input type="number" class="form-control" id="totalCredit" name="total_credit" step="0.01" value="{{ $invoice->total_credit ?? old('total_credit') }}" readonly>
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
    <!-- Existing images will be appended here initially -->
    @if(!empty($invoice->image_path))
        @php
            $existingImages = json_decode($invoice->image_path, true) ?? [];
        @endphp
        @foreach($existingImages as $path)
            <div class="image-container" style="width: 100px; height: 100px; position: relative; display: inline-block;">
                <img src="{{ asset('storage/' . $path) }}" alt="Invoice Image" style="width: 100%; height: 100%; object-fit: cover;">
                <button type="button" class="remove-image-btn" data-path="{{ $path }}" style="position: absolute; top: 0; right: 0; background: red; color: white; border: none; cursor: pointer;">X</button>
                <input type="hidden" name="existing_images[]" value="{{ $path }}">
            </div>
        @endforeach
    @endif
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
        // Select input fields by their IDs
        const previousCredits = document.getElementById('previousCredits');
        const totalTransferred = document.getElementById('totalTransferredRCs');
        const totalCharge = document.getElementById('totalChargeRCs');
        const totalCredit = document.getElementById('totalCredit');
        
        // Function to calculate total credit
        function calculateTotalCredit() {
            const previous = parseFloat(previousCredits.value) || 0;
            const transferred = parseFloat(totalTransferred.value) || 0;
            const charge = parseFloat(totalCharge.value) || 0;
        
            const result = previous + (transferred - charge);
            totalCredit.value = result.toFixed(2); // Set to 2 decimal places
        }
        
        // Event listeners for input fields
        previousCredits.addEventListener('input', calculateTotalCredit);
        totalTransferred.addEventListener('input', calculateTotalCredit);
        totalCharge.addEventListener('input', calculateTotalCredit);
        
        // Initial calculation (in case there is already data on page load)
        calculateTotalCredit();
        </script>
    
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


       // Trigger file input on button click
document.getElementById('addImageButton').addEventListener('click', function() {
    document.getElementById('invoiceImages').click();
});

// Handle new image upload and preview
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

// Remove existing image on 'X' button click
document.querySelectorAll('.remove-image-btn').forEach(button => {
    button.addEventListener('click', function() {
        const imageContainer = button.parentElement;
        const imagePath = button.getAttribute('data-path');
        
        // Remove the image element from preview
        imageContainer.remove();

        // Optionally, mark the image as removed
        const removedImagesInput = document.createElement('input');
        removedImagesInput.type = 'hidden';
        removedImagesInput.name = 'removed_images[]';
        removedImagesInput.value = imagePath;
        document.getElementById('imagePreviewContainer').appendChild(removedImagesInput);
    });
});



        // Update the email field when a company is selected
        document.getElementById('invoiceFor').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            document.getElementById('email').value = selectedOption.getAttribute('data-email');
            document.getElementById('companyId').value = selectedOption.getAttribute('data-id');
        });
    </script>

@endsection
