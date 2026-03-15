@extends('layouts.app')

@section('title', 'Add Donation')
@section('page-title', 'Add New Donation')

@section('page-actions')
    <a href="{{ route('donations.index') }}" class="btn" style="background: #f1f5f9; color: #475569; border: none; border-radius: 30px; padding: 8px 18px; font-weight: 500; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 8px;">
        <i class="fas fa-arrow-left"></i> Back
    </a>
@endsection

@section('content')
<div class="container-fluid px-2 px-sm-3">
    <div class="row justify-content-center g-3">
        <div class="col-12 col-lg-8">
            <!-- Modern Form Card -->
            <div class="" style="border-radius: 24px; overflow: hidden; background: white; box-shadow: 0 20px 40px -12px rgba(0,20,40,0.12); border: 1px solid rgba(226, 232, 240, 0.6);">
                <!-- Card Header -->
                <div class="px-4 py-3" style="background: linear-gradient(145deg, #fafcff, #ffffff); border-bottom: 1px solid rgba(226, 232, 240, 0.6);">
                    <h5 class="mb-0" style="font-family: 'Space Grotesk', sans-serif; font-weight: 600; color: #0b1e33; font-size: 1.1rem;">
                        <i class="fas fa-hand-holding-heart me-2" style="color: #2563eb;"></i>
                        Donation Information
                    </h5>
                </div>
                
                <div class="p-4">
                    <form action="{{ route('donations.store') }}" method="POST" id="donationForm">
                        @csrf
                        
                        <!-- Mobile Number Lookup -->
                        <div class="mb-4">
                            <label class="form-label" style="font-size: 0.9rem; font-weight: 500; color: #334155; margin-bottom: 8px;">
                                <i class="fas fa-phone-alt me-2" style="color: #2563eb;"></i>Mobile Number Lookup
                            </label>
                            <div class="d-flex gap-2">
                                <div class="flex-grow-1 position-relative">
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone_lookup" name="phone_lookup" 
                                           placeholder="Enter mobile number to check existing donor"
                                           style="border-radius: 40px; padding: 10px 16px; border: 1px solid #e2e8f0; background: white; font-size: 0.95rem;">
                                    <div id="lookupSpinner" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); display: none;">
                                        <div class="spinner-border spinner-border-sm" style="color: #2563eb;" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" id="checkDonorBtn" class="btn" style="background: linear-gradient(145deg, #2563eb, #1d4ed8); color: white; border: none; border-radius: 40px; padding: 10px 24px; font-weight: 500; font-size: 0.9rem; white-space: nowrap;">
                                    <i class="fas fa-search me-2"></i>Check
                                </button>
                            </div>
                            <div id="lookupResult" class="mt-2" style="display: none;"></div>
                            <small class="text-secondary d-block mt-1" style="font-size: 0.7rem;">
                                <i class="fas fa-info-circle me-1" style="color: #2563eb;"></i>
                                Enter mobile number to auto-fill donor details if exists
                            </small>
                        </div>

                        <!-- Hidden donor_id field -->
                        <input type="hidden" name="donor_id" id="donor_id" value="">

                        <!-- Donor Information (Now Optional) -->
                        <div class="mb-3 p-3 rounded-3" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="fas fa-user" style="color: #2563eb;"></i>
                                <span style="font-weight: 500; color: #1e293b;">Donor Information (Optional)</span>
                                <small class="text-secondary ms-auto">Fields can be left empty</small>
                            </div>
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label" style="font-size: 0.9rem; font-weight: 500; color: #334155; margin-bottom: 6px;">
                                        Donor Name <span class="text-secondary">(Optional)</span>
                                    </label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" 
                                           placeholder="Enter donor name (optional)"
                                           style="border-radius: 40px; padding: 10px 16px; border: 1px solid #e2e8f0; background: white; font-size: 0.95rem;">
                                    @error('name')
                                        <div class="invalid-feedback" style="font-size: 0.8rem; margin-top: 4px;">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="phone" class="form-label" style="font-size: 0.9rem; font-weight: 500; color: #334155; margin-bottom: 6px;">
                                        Phone Number <span class="text-secondary">(Optional)</span>
                                    </label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone') }}" 
                                           placeholder="01XXXXXXXXX (optional)"
                                           style="border-radius: 40px; padding: 10px 16px; border: 1px solid #e2e8f0; background: white; font-size: 0.95rem;">
                                    @error('phone')
                                        <div class="invalid-feedback" style="font-size: 0.8rem; margin-top: 4px;">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Donation Details -->
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="amount" class="form-label" style="font-size: 0.9rem; font-weight: 500; color: #334155; margin-bottom: 6px;">
                                    Amount (৳) <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 40px 0 0 40px; border-right: none; color: #475569;">৳</span>
                                    <input type="number" step="1" min="1" 
                                           class="form-control @error('amount') is-invalid @enderror" 
                                           id="amount" name="amount" value="{{ old('amount') }}" 
                                           placeholder="0" required
                                           style="border-radius: 0 40px 40px 0; padding: 10px 16px; border: 1px solid #e2e8f0; background: white; font-size: 0.95rem;">
                                </div>
                                @error('amount')
                                    <div class="invalid-feedback" style="font-size: 0.8rem; margin-top: 4px;">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="payment_method" class="form-label" style="font-size: 0.9rem; font-weight: 500; color: #334155; margin-bottom: 6px;">
                                    Payment Method
                                </label>
                                <select class="form-select @error('payment_method') is-invalid @enderror" 
                                        id="payment_method" name="payment_method" required
                                        style="border-radius: 40px; padding: 10px 16px; border: 1px solid #e2e8f0; background: white; font-size: 0.95rem;">
                                    <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="bkash" {{ old('payment_method') == 'bkash' ? 'selected' : '' }}>bKash</option>
                                    <option value="nagad" {{ old('payment_method') == 'nagad' ? 'selected' : '' }}>Nagad</option>
                                </select>
                                @error('payment_method')
                                    <div class="invalid-feedback" style="font-size: 0.8rem; margin-top: 4px;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row g-3 mb-4">
                            {{-- <div class="col-md-6">
                                <label for="paid_status" class="form-label" style="font-size: 0.9rem; font-weight: 500; color: #334155; margin-bottom: 6px;">
                                    Payment Status
                                </label>
                                <select class="form-select @error('paid_status') is-invalid @enderror" 
                                        id="paid_status" name="paid_status" required
                                        style="border-radius: 40px; padding: 10px 16px; border: 1px solid #e2e8f0; background: white; font-size: 0.95rem;">
                                    <option value="paid" {{ old('paid_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                    <option value="unpaid" {{ old('paid_status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                                </select>
                                @error('paid_status')
                                    <div class="invalid-feedback" style="font-size: 0.8rem; margin-top: 4px;">{{ $message }}</div>
                                @enderror
                            </div> --}}
                            
                            <div class="col-md-6">
                                <label for="notes" class="form-label" style="font-size: 0.9rem; font-weight: 500; color: #334155; margin-bottom: 6px;">
                                    Notes (Optional)
                                </label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" 
                                          id="notes" name="notes" rows="1" 
                                          placeholder="Any additional notes"
                                          style="border-radius: 30px; padding: 10px 16px; border: 1px solid #e2e8f0; background: white; font-size: 0.95rem; resize: vertical;">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback" style="font-size: 0.8rem; margin-top: 4px;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <hr style="border-top: 1px dashed #e2e8f0; margin: 24px 0;">
                        
                        <!-- Form Actions -->
                        <div class="d-flex flex-column flex-sm-row justify-content-end gap-2">
                            <a href="{{ route('donations.index') }}" class="btn w-100 w-sm-auto" style="background: #ef1010; color: white; border: none; border-radius: 40px; padding: 10px 24px; font-weight: 500; font-size: 0.9rem; display: inline-flex; align-items: center; justify-content: center; gap: 8px;">
                                Cancel
                            </a>
                            <button type="submit" class="btn w-100 w-sm-auto" style="background: linear-gradient(145deg, #2563eb, #1d4ed8); color: white; border: none; border-radius: 40px; padding: 10px 24px; font-weight: 500; font-size: 0.9rem; display: inline-flex; align-items: center; justify-content: center; gap: 8px; box-shadow: 0 10px 20px -8px rgba(37,99,235,0.4);">
                                <i class="fas fa-save me-2"></i>Save Donation
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Form focus states */
.form-control:focus, .form-select:focus {
    border-color: #2563eb !important;
    box-shadow: 0 0 0 3px rgba(37,99,235,0.15) !important;
    outline: none;
}

/* Lookup result styles */
.donor-found {
    background: #ecfdf5;
    border: 1px solid #86efac;
    border-radius: 12px;
    padding: 10px 16px;
    color: #166534;
    font-size: 0.85rem;
    display: flex;
    align-items: center;
    gap: 10px;
}

.donor-not-found {
    background: #fff7ed;
    border: 1px solid #fed7aa;
    border-radius: 12px;
    padding: 10px 16px;
    color: #9a3412;
    font-size: 0.85rem;
    display: flex;
    align-items: center;
    gap: 10px;
}

/* Mobile optimizations */
@media (max-width: 768px) {
    .content-card {
        border-radius: 20px !important;
    }
    
    .p-4 {
        padding: 16px !important;
    }
    
    .btn {
        min-height: 44px;
    }
    
    .form-control, .form-select {
        font-size: 16px !important;
        padding: 12px 16px !important;
    }
}

/* Hover effects */
.btn:hover {
    transform: translateY(-2px);
    transition: all 0.2s;
}
</style>

@push('scripts')
<script>
$(document).ready(function() {
    let searchTimeout;
    let currentDonorId = null;
    let lastCheckedPhone = ''; // Track the last phone number that was successfully checked

    // Check donor by phone number
    $('#checkDonorBtn').on('click', function() {
        checkDonorByPhone();
    });

    $('#phone_lookup').on('keypress', function(e) {
        if (e.which === 13) { // Enter key
            e.preventDefault();
            checkDonorByPhone();
        }
    });

    // Auto-check as user types (with debounce)
    $('#phone_lookup').on('input', function() {
        clearTimeout(searchTimeout);
        const phone = $(this).val();
        const previousPhone = lastCheckedPhone;
        
        // If the phone number has changed AT ALL, clear the donor fields
        if (phone !== previousPhone) {
            clearDonorFields();
        }
        
        if (phone.length >= 8) { // Start checking after 8 digits
            searchTimeout = setTimeout(function() {
                checkDonorByPhone();
            }, 800);
        }
    });

    function checkDonorByPhone() {
        const phone = $('#phone_lookup').val().trim();
        
        if (phone.length < 8) {
            $('#lookupResult').html(`
                <div class="donor-not-found">
                    <i class="fas fa-exclamation-triangle"></i>
                    Please enter at least 8 digits
                </div>
            `).show();
            return;
        }

        // Show spinner
        $('#lookupSpinner').show();
        $('#checkDonorBtn').prop('disabled', true);

        // Make AJAX request to check donor
        $.ajax({
            url: '{{ route("donations.checkDonor") }}',
            method: 'POST',
            data: {
                phone: phone,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
    $('#lookupSpinner').hide();
    $('#checkDonorBtn').prop('disabled', false);

    if (response.success) {
        if (response.type === 'donor') {
            // For monthly donor - set donor_id
            $('#donor_id').val(response.donor.id);
            $('#name').val(response.donor.name);
            $('#phone').val(response.donor.phone);
            
            $('#lookupResult').html(`
                <div class="donor-found">
                    <i class="fas fa-check-circle"></i>
                    <div>
                        <strong>Monthly Donor Found!</strong><br>
                        Name: ${response.donor.name}<br>
                        Phone: ${response.donor.phone}
                    </div>
                </div>
            `).show();
        } else {
            // For random donor/contributor - set donor_id to empty string
            $('#donor_id').val(''); // Empty string, will be converted to null in controller
            $('#name').val(response.donor.name);
            $('#phone').val(response.donor.phone);
            
            $('#lookupResult').html(`
                <div class="donor-found">
                    <i class="fas fa-check-circle"></i>
                    <div>
                        <strong>Random Donor Found!</strong><br>
                        Name: ${response.donor.name}<br>
                        Phone: ${response.donor.phone}<br>
                        <small>Will be saved as contributor</small>
                    </div>
                </div>
            `).show();
        }
        
        lastCheckedPhone = phone;
    } else {
        // Donor not found - clear donor_id
        $('#donor_id').val('');
        $('#name').val('');
        $('#phone').val(phone);
        
        $('#lookupResult').html(`
            <div class="donor-not-found">
                <i class="fas fa-info-circle"></i>
                <div>
                    <strong>New Donor</strong><br>
                    No existing donor found with this number.<br>
                    Phone number has been auto-filled. You can enter name or leave it empty.
                </div>
            </div>
        `).show();
        
        lastCheckedPhone = '';
    }
},
            error: function(xhr) {
                $('#lookupSpinner').hide();
                $('#checkDonorBtn').prop('disabled', false);
                
                $('#lookupResult').html(`
                    <div class="donor-not-found">
                        <i class="fas fa-exclamation-circle"></i>
                        Error checking donor. Please try again.
                    </div>
                `).show();
                
                // Reset last checked phone on error
                lastCheckedPhone = '';
            }
        });
    }

    // Function to clear all donor-related fields
    function clearDonorFields() {
        // Only clear if they are not empty and not manually edited
        if ($('#name').val() !== '' || $('#phone').val() !== '' || $('#donor_id').val() !== '') {
            currentDonorId = null;
            $('#donor_id').val('');
            $('#name').val('');
            $('#phone').val('');
            $('#lookupResult').hide();
            $('#lookupResult').html('');
            
            console.log('Donor fields cleared due to phone number change');
        }
    }

    // Manual entry - clear donor_id if name is edited
    $('#name, #phone').on('input', function() {
        const currentDonorId = $('#donor_id').val();
        if (currentDonorId) {
            // If user manually edits fields, clear the donor_id
            $('#donor_id').val('');
            $('#lookupResult').html(`
                <div class="donor-not-found">
                    <i class="fas fa-info-circle"></i>
                    Manual entry mode - will be saved as new donor
                </div>
            `).show();
            
            // Reset last checked phone since manual entry overrides
            lastCheckedPhone = '';
        }
    });

    // Clear lookup result when phone field is focused and empty
    $('#phone_lookup').on('focus', function() {
        if (!$(this).val()) {
            $('#lookupResult').hide();
        }
    });

    // Format amount input
    $('#amount').on('input', function() {
        var value = $(this).val().replace(/,/g, '');
        if (!isNaN(value) && value.length > 0) {
            $(this).val(value);
        }
    });

    // Optional: Add a clear button functionality
    // Show/hide clear button based on input
    $('#phone_lookup').on('input', function() {
        if ($(this).val().length > 0) {
            $('#clearLookup').show();
        } else {
            $('#clearLookup').hide();
        }
    });

    // Clear button functionality (if you add the button)
    $('#clearLookup').on('click', function() {
        $('#phone_lookup').val('').focus();
        clearDonorFields();
        $(this).hide();
        lastCheckedPhone = '';
    });
});
</script>
@endpush
@endsection