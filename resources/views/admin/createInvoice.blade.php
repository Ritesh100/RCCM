@extends('admin.sidebar')

@section('content')

<div class="container">
    <h2>Create New Invoice</h2>

    <!-- Your form to create an invoice -->
    <form id="invoiceForm"  method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Invoice for Week -->
        <div class="mb-3">
            <label for="invoiceWeek" class="form-label">Invoice for Week</label>
            <input type="text" class="form-control" id="invoiceWeek" name="invoice_week" required>
        </div>

        <!-- Invoice for -->
        <div class="mb-3">
            <label for="invoiceFor" class="form-label">Invoice For</label>
            <input type="text" class="form-control" id="invoiceFor" name="invoice_for" required>
        </div>

        <!-- Email -->
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>

        <!-- Invoice From -->
        <div class="mb-3">
            <label for="invoiceFrom" class="form-label">Invoice From</label>
            <input type="text" class="form-control" id="invoiceFrom" name="invoice_from" required>
        </div>

        <!-- Invoice Address From -->
        <div class="mb-3">
            <label for="invoiceAddressFrom" class="form-label">Invoice Address From</label>
            <input type="text" class="form-control" id="invoiceAddressFrom" name="invoice_address_from" required>
        </div>

        <!-- Contact Email -->
        <div class="mb-3">
            <label for="contactEmail" class="form-label">Contact Email</label>
            <input type="email" class="form-control" id="contactEmail" name="contact_email" required>
        </div>

        <!-- Invoice Number -->
        <div class="mb-3">
            <label for="invoiceNumber" class="form-label">Invoice Number</label>
            <input type="text" class="form-control" id="invoiceNumber" name="invoice_number" required>
        </div>

        <div class="mb-3">
            <label for="charge1Name" class="form-label">Charge Name</label>
            <input type="text" class="form-control" id="charge1Name" name="charge_1_name" required>
        </div>
        <div class="mb-3">
            <label for="charge1Total" class="form-label">Charge Total</label>
            <input type="number" class="form-control" id="charge1Total" name="charge_1_total" step="0.01" required>
        </div>

        <div id="additionalChargesContainer">
        </div>

        <div class="mb-3">
            <button type="button" id="addChargeButton" class="btn btn-outline-primary">
                <i class="bi bi-plus-lg"></i> Add Charge
            </button>
        </div>
        <div class="mb-3">
            <label for="totalChargeRCs" class="form-label">Total Charge for RCs</label>
            <input type="number" class="form-control" id="totalChargeRCs" name="total_charge_rcs" step="0.01" required>
        </div>

        <!-- Total Transferred to RCs -->
        <div class="mb-3">
            <label for="totalTransferredRCs" class="form-label">Total Transferred to RCs</label>
            <input type="number" class="form-control" id="totalTransferredRCs" name="total_transferred_rcs" step="0.01" required>
        </div>

        <!-- Previous Credits -->
        <div class="mb-3">
            <label for="previousCredits" class="form-label">Previous Credits</label>
            <input type="number" class="form-control" id="previousCredits" name="previous_credits" step="0.01" required>
        </div>

        <div class="mb-3">
            <label for="invoiceImages" class="form-label">Upload Invoice Images</label>
            <!-- Hidden File Input -->
            <input type="file" class="form-control d-none" id="invoiceImages" name="invoice_images[]" accept="image/*" multiple>

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
            <button type="submit" class="btn btn-success">Submit Invoice</button>
        </div>
    </form>
</div>
@endsection


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
