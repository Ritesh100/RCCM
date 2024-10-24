@extends('admin.sidebar')

@section('content')
    <div class="container">
        <h2>Create New Invoice</h2>

        <!-- Your form to create an invoice -->
        <form action = "{{ route('admin.storeInvoice') }}" id="invoiceForm" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Invoice for Week -->
            <div class="mb-3">
                <label for="invoiceforweek" class="form-label">Invoice For Week</label>
                <div class="row g-3 align-items-center">
                    <div class="col-md-6">
                        <label for="week_start" class="form-label">Select Week Start:</label>
                        <input type="date" name="week_start" id="week_start" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label for="week_end" class="form-label">Select Week End:</label>
                        <input type="date" name="week_end" id="week_end" class="form-control" required>
                    </div>
                </div>

            </div>

            <!-- Invoice for -->
            <div class="mb-3">
                <label for="invoiceFor" class="form-label">Invoice For</label>
                <select name="invoice_for" id="invoiceFor" class="form-control">
                    <option value="" disabled selected>Select a user</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" data-email="{{ $user->email }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value ="" required readonly>
            </div>

            <!-- Invoice From -->
            <div class="mb-3">
                <label for="invoiceFrom" class="form-label">Invoice From</label>
                <input type="text" class="form-control" id="invoiceFrom" name="invoice_from"
                    value="{{ $admin->userName }}" required readonly>
            </div>

            <!-- Invoice Address From -->
            <div class="mb-3">
                <label for="invoiceAddressFrom" class="form-label">Invoice Address From</label>
                <input type="text" class="form-control" id="invoiceAddressFrom" name="invoice_address_from"
                    value="{{ $admin->address }}" required readonly>
            </div>

            <!-- Contact Email -->
            <div class="mb-3">
                <label for="contactEmail" class="form-label">Contact Email</label>
                <input type="email" class="form-control" id="contactEmail" name="contact_email"
                    value="{{ $admin->userEmail }}" required readonly>
            </div>

            <!-- Invoice Number -->
            <div class="mb-3">
                <label for="invoiceNumber" class="form-label">Invoice Number</label>
                <input type="text" class="form-control" id="invoiceNumber" name="invoice_number"
                    value="{{ $invoice_number }}" required>
            </div>

            <div class="mb-3">
                <label for="charge1Name" class="form-label">Charge Name</label>
                <input type="text" class="form-control" id="charge1Name" name="charges[0][name]" required>
            </div>
            <div class="mb-3">
                <label for="charge1Total" class="form-label">Charge Total</label>
                <input type="number" class="form-control" id="charge1Total" name="charges[0][total]" step="0.01"
                    required>
            </div>

            <!-- Container for additional charges -->
            <div id="additionalChargesContainer"></div>

            <!-- Button to add more charges -->
            <div class="mb-3">
                <button type="button" id="addChargeButton" class="btn btn-outline-primary">
                    <i class="bi bi-plus-lg"></i> Add Charge
                </button>
            </div>

            <div class="mb-3">
                <label for="totalChargeRCs" class="form-label">Total Charge for RCs</label>
                <input type="number" class="form-control" id="totalChargeRCs" name="total_charge_rcs" step="0.01"
                    required>
            </div>

            <!-- Total Transferred to RCs -->
            <div class="mb-3">
                <label for="totalTransferredRCs" class="form-label">Total Transferred to RCs</label>
                <input type="number" class="form-control" id="totalTransferredRCs" name="total_transferred_rcs"
                    step="0.01" required>
            </div>

            <!-- Previous Credits -->
            <div class="mb-3">
                <label for="previousCredits" class="form-label">Previous Credits</label>
                <input type="number" class="form-control" id="previousCredits" name="previous_credits" step="0.01"
                    required>
            </div>

            <div class="mb-3">
                <label for="invoiceImages" class="form-label">Upload Invoice Images</label>
                <!-- Hidden File Input -->
                <input type="file" class="form-control d-none" id="invoiceImages" name="invoice_images[]"
                    accept="image/*" multiple>

                <!-- + Icon to trigger file selection -->
                <button type="button" id="addImageButton" class="btn btn-outline-primary" style="font-size: 24px;">
                    <i class="bi bi-plus-lg"></i> <!-- Bootstrap Icons 'plus' -->
                </button>
            </div>

            <!-- Image Preview Container -->
            <div class="mb-3" id="imagePreviewContainer" style="display: flex; flex-wrap: wrap; gap: 10px;">
                <!-- Previewed images will appear here -->
            </div>

            <!-- Submit Button -->
            <div class="mb-3">
                <button type="submit" class="btn btn-success">Submit</button>
            </div>

        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Initialize the index for additional charges
        let chargeIndex = 1;

        // Event listener for the "Add Charge" button
        document.getElementById('addChargeButton').addEventListener('click', function() {
            // Create a new div to hold the charge name and total inputs
            const newChargeDiv = document.createElement('div');
            newChargeDiv.classList.add('mb-3');

            // Charge Name Label
            const chargeNameLabel = document.createElement('label');
            chargeNameLabel.classList.add('form-label');
            chargeNameLabel.innerText = `Charge Name ${chargeIndex + 1}`;

            // Charge Name Input
            const chargeNameInput = document.createElement('input');
            chargeNameInput.type = 'text';
            chargeNameInput.classList.add('form-control');
            chargeNameInput.name = `charges[${chargeIndex}][name]`;
            chargeNameInput.required = true;

            // Charge Total Label
            const chargeTotalLabel = document.createElement('label');
            chargeTotalLabel.classList.add('form-label');
            chargeTotalLabel.innerText = `Charge Total ${chargeIndex + 1}`;

            // Charge Total Input
            const chargeTotalInput = document.createElement('input');
            chargeTotalInput.type = 'number';
            chargeTotalInput.classList.add('form-control');
            chargeTotalInput.name = `charges[${chargeIndex}][total]`;
            chargeTotalInput.step = '0.01';
            chargeTotalInput.required = true;

            // Append label and input fields for charge name and total to the new div
            newChargeDiv.appendChild(chargeNameLabel);
            newChargeDiv.appendChild(chargeNameInput);
            newChargeDiv.appendChild(chargeTotalLabel);
            newChargeDiv.appendChild(chargeTotalInput);

            // Append the new div to the additional charges container
            document.getElementById('additionalChargesContainer').appendChild(newChargeDiv);

            // Increment the charge index
            chargeIndex++;
        });
        document.getElementById('addImageButton').addEventListener('click', function() {
            // Trigger the hidden file input when + icon is clicked
            document.getElementById('invoiceImages').click();
        });

        document.getElementById('invoiceImages').addEventListener('change', function(event) {
            const files = event.target.files;
            const imagePreviewContainer = document.getElementById('imagePreviewContainer');

            // Loop through the selected files
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const reader = new FileReader();

                reader.onload = function(e) {
                    // Create a container for the image preview with close icon
                    const imageContainer = document.createElement('div');
                    imageContainer.style.width = '100px';
                    imageContainer.style.height = '100px';
                    imageContainer.style.position = 'relative';
                    imageContainer.style.border = '1px solid #ccc';
                    imageContainer.style.borderRadius = '10px';
                    imageContainer.style.overflow = 'hidden';

                    // Create an img element for the preview
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.width = '100%';
                    img.style.height = '100%';
                    img.style.objectFit = 'cover'; // Ensures the image fills the square

                    // Create a close button (X icon)
                    const closeButton = document.createElement('button');
                    closeButton.innerHTML = '✖';
                    closeButton.style.position = 'absolute';
                    closeButton.style.top = '5px';
                    closeButton.style.right = '5px';
                    closeButton.style.background = 'rgba(255, 255, 255, 0.7)';
                    closeButton.style.border = 'none';
                    closeButton.style.color = '#f00';
                    closeButton.style.fontSize = '16px';
                    closeButton.style.cursor = 'pointer';

                    // Event to remove the image when close button is clicked
                    closeButton.addEventListener('click', function() {
                        imagePreviewContainer.removeChild(imageContainer);
                    });

                    // Append img and close button to the image container
                    imageContainer.appendChild(img);
                    imageContainer.appendChild(closeButton);

                    // Append the image container to the preview section
                    imagePreviewContainer.appendChild(imageContainer);
                };

                reader.readAsDataURL(file);
            }

            // Reset file input so same file can be added again if necessary
            event.target.value = '';
        });
    </script>
@endsection





<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Ensure JavaScript is running after DOM is fully loaded
        document.getElementById('invoiceFor').addEventListener('change', function() {
            // Get the selected option from the dropdown
            const selectedOption = this.options[this.selectedIndex];

            // Get the email from the selected option's data-email attribute
            const email = selectedOption.getAttribute('data-email');

            // Populate the email input field with the selected user's email
            document.getElementById('email').value = email;
        });
    });
</script>
