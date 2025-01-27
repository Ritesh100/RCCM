

@extends('admin.sidebar')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600&display=swap" rel="stylesheet">


<style>
    body {
        font-family: 'Open Sans', sans-serif;
        background-color: #f0f2f5; /* Light gray background */
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh; /* Full viewport height */
        padding: 20px; /* Add padding for better spacing */
    }
    .btn-custom {
        background-color: white !important;
        color: #5271ff !important;
        border: 2px solid #5271ff !important;
    }

    .btn-custom:hover {
        background-color: #5271ff !important;
        color: white !important;
    }
</style>
@section('content')
    <div class="container-fluid">
        <h2 class="text-center mb-4">Create New Invoice</h2>

        <!-- Your form to create an invoice -->
        <form action="/admin/invoicePost" id="invoiceForm" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Invoice for Week -->
            <div class="p-4 shadow rounded bg-light mx-auto">
                <div class="row g-3 align-items-center">
                    <h5 class="">Invoice For Week</h5>
                    <div class="col-md-6">
                        <label for="week_start" class="form-label">Select Week Start:</label>
                        <input type="date" name="week_start" id="week_start" class="form-control" required>
                        @error('week_start')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="week_end" class="form-label">Select Week End:</label>
                        <input type="date" name="week_end" id="week_end" class="form-control" required>
                        @error('week_end')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Invoice for -->
                    <h5 class="">Invoice For</h5>
                    <div class="col-md-6">
                        <label for="invoice_for" class="form-label">Select Company</label>
                        <select name="invoice_for" id="invoiceFor" class="form-select" required>
                            <option value="" disabled selected>Select a Company</option>
                            @foreach ($companies as $company)
                                <option value="{{ $company->name }}" data-id="{{ $company->id }}" data-email="{{ $company->email }}">
                                    {{ $company->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('invoice_for')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                        <input type="hidden" name="company_id" id="companyId">
                    </div>

                    <!-- Email -->
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required readonly>
                        @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Invoice From -->
                    <h5 class="">Invoice From</h5>
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
                    <option value="" disabled selected>Select Currency</option>
                    <option value="AUD">Australia (AUD)</option>
                    <option value="NPR">Nepal (NPR)</option>
                    <option value="INR">India (INR)</option>
                    <option value="USD">United States (USD)</option>
                    <option value="CAD">Canada (CAD)</option>
                </select>
                @error('currency')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div> 
                    <!-- Invoice Number -->
                    <div class="col-md-6">
                        <label for="invoiceNumber" class="form-label">Invoice Number</label>
                        <input type="text" class="form-control" id="invoiceNumber" name="invoice_number" value="{{ $invoice_number }}" required>
                        @error('invoice_number')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
            </div>

                    <!-- Initial Charge Fields -->
                    <h5 class="g-3">Charges</h5>
                    <div class="row " id="initialCharge">
                        <div class="col-md-6">
                            <label for="charge1Name" class="form-label">Charge Name</label>
                            <input type="text" class="form-control" id="charge1Name" name="charges[0][name]" required>
                            @error('charges.0.name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="charge1Total" class="form-label">Charge Total</label>
                            <input type="number" class="form-control" id="charge1Total" name="charges[0][total]" step="0.01" required>
                            @error('charges.0.total')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
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
                        <label for="totalCharge" class="form-label">Total Transferred RCS</label>
                        <input type="number" class="form-control" id="totalCharge" name="total_charge_rcs" step="0.01" value="{{ old('total_charge_rcs') ?? 0 }}">
                    </div>

                     <div class="col-md-6">
                        <label for="totalTransferred" class="form-label">Total Charge RCS</label>
                        <input type="number" class="form-control" id="totalTransferred" name="total_transferred_rcs" step="0.01" value="{{ old('total_transferred_rcs') ?? 0 }}">
                    </div>

                    <div class="col-md-6">
                        <label for="previousCredits" class="form-label">Previous Credits</label>
                        <input type="number" class="form-control" id="previousCredits" name="previous_credits" step="0.01" value="{{ old('previous_credits') ?? 0 }}" readonly>
                    </div>
                    
                               
                
                    <!-- Total Credit Field (Calculated) -->
                    <div class="col-md-6">
                        <label for="totalCredit" class="form-label">Total Credit</label>
                        <input type="number" class="form-control" id="totalCredit" name="total_credit" step="0.01" value="{{ old('total_credit') ?? 0 }}" readonly>
                    </div>


                    <div class="col-md-6">
                        <label for="invoiceImages" class="form-label">Upload Invoice Images</label>
                        <!-- Hidden File Input -->
                        <input type="file" class="form-control d-none" id="invoiceImages" name="invoice_images[]" accept="image/*" multiple>

                        <!-- + Icon to trigger file selection -->
                        <button type="button" id="addImageButton" class="btn btn-outline-primary" style="font-size: 24px;">
                            <i class="bi bi-plus-lg"></i>
                        </button>
                    </div>

                    <!-- Image Preview Container -->
                    <div class="mb-4" id="imagePreviewContainer" style="display: flex; flex-wrap: wrap; gap: 10px;"></div>

                    <!-- Submit Button -->
                    <div class="mb-4">
                        <button type="submit" class="btn btn-custom">Submit</button>
                    </div>

                </div>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.getElementById('invoiceFor').addEventListener('change', function() {
            var selectedCompany = this.value;
    
            if (selectedCompany) {
                // Make an AJAX request to fetch the previous credits
                fetch(`/get-previous-credits/${selectedCompany}`)
                    .then(response => response.json())
                    .then(data => {
                        // Set the previous_credits field with the returned value
                        document.getElementById('previousCredits').value = data.previous_credits;
                    })
                    .catch(error => console.error('Error fetching previous credits:', error));
            } else {
                // If no company is selected, set previous_credits to 0
                document.getElementById('previousCredits').value = 0;
            }
        });
    </script>
    
     


    <script>
        // Select input fields
        const previousCredits = document.getElementById('previousCredits');
        const totalTransferred = document.getElementById('totalTransferred');
        const totalCharge = document.getElementById('totalCharge');
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

        // Update the email field when a company is selected
        document.getElementById('invoiceFor').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const email = selectedOption.getAttribute('data-email');
            constcompanyId = selectedOption.getAttribute('data-id'); // Get the selectedcompany's ID
            constcompanyName = selectedOption.value; // Get the selectedcompany's name

            // Update the email input field
            document.getElementById('email').value = email;

            // Update the hiddencompany_id field with the selected ID
            document.getElementById('companyId').value =companyId;

            // Update the form action URL with thecompany ID if needed
            const form = document.getElementById('invoiceForm');
            form.action = `/admin/invoicePost/${companyId}`;

            console.log(`Company ID: ${companyId}, Company Name: ${companyName}`);
        });
    </script>
@endsection
