@extends('layouts.app')

@section('title', 'Edit Donation')
@section('page-title', 'Edit Donation')
@section('page-subtitle', 'Update donation information')

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
                <!-- Card Header with Status Badge -->
                <div class="px-4 py-3 d-flex justify-content-between align-items-center" style="background: linear-gradient(145deg, #fafcff, #ffffff); border-bottom: 1px solid rgba(226, 232, 240, 0.6);">
                    <h5 class="mb-0" style="font-family: 'Space Grotesk', sans-serif; font-weight: 600; color: #0b1e33; font-size: 1.1rem;">
                        <i class="fas fa-hand-holding-heart me-2" style="color: #2563eb;"></i>
                        Edit Donation #{{ $donation->id }}
                    </h5>
                    <span class="badge rounded-pill px-3 py-2" style="background: {{ $donation->paid_status == 'paid' ? 'rgba(16, 185, 129, 0.1)' : 'rgba(239, 68, 68, 0.1)' }}; color: {{ $donation->paid_status == 'paid' ? '#10b981' : '#ef4444' }}; font-size: 0.7rem;">
                        <i class="fas fa-{{ $donation->paid_status == 'paid' ? 'check-circle' : 'clock' }} me-1"></i>
                        Current: {{ ucfirst($donation->paid_status) }}
                    </span>
                </div>
                
                <div class="p-4">
                    <form action="{{ route('donations.update', $donation) }}" method="POST" id="donationForm">
                        @csrf
                        @method('PUT')
                        
                        <!-- Mobile Number Lookup -->
                        <div class="mb-4">
                            <label class="form-label" style="font-size: 0.9rem; font-weight: 500; color: #334155; margin-bottom: 8px;">
                                <i class="fas fa-phone-alt me-2" style="color: #2563eb;"></i>Mobile Number Lookup
                            </label>
                            <div class="d-flex gap-2">
                                <div class="flex-grow-1 position-relative">
                                    <input type="text" class="form-control" 
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
                        <input type="hidden" name="donor_id" id="donor_id" value="{{ $donation->donor_id }}">

                        <!-- Donor Information (Optional) -->
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
                                           id="name" name="name" value="{{ old('name', $donation->name) }}" 
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
                                           id="phone" name="phone" value="{{ old('phone', $donation->phone) }}" 
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
                                           id="amount" name="amount" value="{{ old('amount', $donation->amount) }}" 
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
                                    <option value="cash" {{ old('payment_method', $donation->payment_method) == 'cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="bkash" {{ old('payment_method', $donation->payment_method) == 'bkash' ? 'selected' : '' }}>bKash</option>
                                    <option value="nagad" {{ old('payment_method', $donation->payment_method) == 'nagad' ? 'selected' : '' }}>Nagad</option>
                                </select>
                                @error('payment_method')
                                    <div class="invalid-feedback" style="font-size: 0.8rem; margin-top: 4px;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="paid_status" class="form-label" style="font-size: 0.9rem; font-weight: 500; color: #334155; margin-bottom: 6px;">
                                    Payment Status
                                </label>
                                <select class="form-select @error('paid_status') is-invalid @enderror" 
                                        id="paid_status" name="paid_status" required
                                        style="border-radius: 40px; padding: 10px 16px; border: 1px solid #e2e8f0; background: white; font-size: 0.95rem;">
                                    <option value="paid" {{ old('paid_status', $donation->paid_status) == 'paid' ? 'selected' : '' }}>Paid</option>
                                    <option value="unpaid" {{ old('paid_status', $donation->paid_status) == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                                </select>
                                @error('paid_status')
                                    <div class="invalid-feedback" style="font-size: 0.8rem; margin-top: 4px;">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="notes" class="form-label" style="font-size: 0.9rem; font-weight: 500; color: #334155; margin-bottom: 6px;">
                                    Notes (Optional)
                                </label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" 
                                          id="notes" name="notes" rows="1" 
                                          placeholder="Any additional notes"
                                          style="border-radius: 30px; padding: 10px 16px; border: 1px solid #e2e8f0; background: white; font-size: 0.95rem; resize: vertical;">{{ old('notes', $donation->notes) }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback" style="font-size: 0.8rem; margin-top: 4px;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Record Info - Read-only (for reference) -->
                        <div class="mb-3 p-3 rounded-3" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-secondary d-block" style="font-size: 0.65rem;">CREATED</small>
                                    <span style="font-size: 0.8rem;">{{ $donation->created_at->format('M d, Y \a\t h:i A') }}</span>
                                    <small class="text-secondary d-block mt-1">by {{ $donation->user->name }}</small>
                                </div>
                                <div class="text-end">
                                    <small class="text-secondary d-block" style="font-size: 0.65rem;">LAST UPDATED</small>
                                    <span style="font-size: 0.8rem;">{{ $donation->updated_at->format('M d, Y \a\t h:i A') }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <hr style="border-top: 1px dashed #e2e8f0; margin: 24px 0;">
                        
                        <!-- Form Actions -->
                        <div class="d-flex flex-column flex-sm-row justify-content-end gap-2">
                            <a href="{{ route('donations.index') }}" class="btn w-100 w-sm-auto" style="background: #f1f5f9; color: #475569; border: none; border-radius: 40px; padding: 10px 24px; font-weight: 500; font-size: 0.9rem; display: inline-flex; align-items: center; justify-content: center; gap: 8px;">
                                Cancel
                            </a>
                            <button type="submit" class="btn w-100 w-sm-auto" style="background: linear-gradient(145deg, #2563eb, #1d4ed8); color: white; border: none; border-radius: 40px; padding: 10px 24px; font-weight: 500; font-size: 0.9rem; display: inline-flex; align-items: center; justify-content: center; gap: 8px; box-shadow: 0 10px 20px -8px rgba(37,99,235,0.4);">
                                <i class="fas fa-save me-2"></i>Update Donation
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Danger Zone - Delete Section (Separate Card) -->
            <div class="mt-3" style="border-radius: 24px; overflow: hidden; background: white; box-shadow: 0 20px 40px -12px rgba(0,20,40,0.12); border: 1px solid rgba(239, 68, 68, 0.2);">
                <div class="px-4 py-2" style="background: linear-gradient(145deg, #fef2f2, #fee2e2); border-bottom: 1px solid rgba(239, 68, 68, 0.2);">
                    <h6 class="mb-0" style="font-weight: 600; color: #991b1b; font-size: 0.9rem;">
                        <i class="fas fa-exclamation-triangle me-2" style="color: #dc2626;"></i>
                        Danger Zone
                    </h6>
                </div>
                <div class="p-3">
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
                        <div>
                            <p class="mb-0" style="font-size: 0.8rem; color: #4b5563;">Delete this donation permanently</p>
                            <small style="font-size: 0.65rem; color: #6b7280;">This action cannot be undone.</small>
                        </div>
                        <form action="{{ route('donations.destroy', $donation) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this donation? This action cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn" style="background: white; border: 1px solid #ef4444; color: #ef4444; border-radius: 40px; padding: 8px 20px; font-size: 0.8rem;">
                                <i class="fas fa-trash me-2"></i>Delete Permanently
                            </button>
                        </form>
                    </div>
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
    
    /* Better touch targets */
    .form-control, .form-select {
        font-size: 16px !important; /* Prevents zoom on iOS */
        padding: 12px 16px !important;
    }
    
    .btn-check + .btn {
        padding: 12px 8px !important;
        font-size: 0.85rem !important;
    }
}

/* Hover effects */
.btn:hover {
    transform: translateY(-2px);
    transition: all 0.2s;
}

.btn-check + .btn:hover {
    background: #eef2ff !important;
    border-color: #2563eb !important;
    color: #1e293b !important;
}

.btn-check:checked + .btn:hover {
    background: linear-gradient(145deg, #2563eb, #1d4ed8) !important;
    color: white !important;
}

/* Danger zone hover */
.btn-outline-danger:hover {
    background: #ef4444 !important;
    color: white !important;
}
</style>

@push('scripts')
<script>
$(document).ready(function() {
    let searchTimeout;
    let currentDonorId = {{ $donation->donor_id ?? 'null' }};
    let lastCheckedPhone = '{{ $donation->phone ?? '' }}';
    
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
                    // Donor found - auto-fill and set donor_id
                    currentDonorId = response.donor.id;
                    $('#donor_id').val(response.donor.id);
                    $('#name').val(response.donor.name);
                    $('#phone').val(response.donor.phone);
                    
                    // Store the phone that was successfully checked
                    lastCheckedPhone = phone;
                    
                    $('#lookupResult').html(`
                        <div class="donor-found">
                            <i class="fas fa-check-circle"></i>
                            <div>
                                <strong>Donor Found!</strong><br>
                                Name: ${response.donor.name}<br>
                                Phone: ${response.donor.phone}
                            </div>
                        </div>
                    `).show();

                    // Highlight the fields
                    $('#name, #phone').addClass('border-success');
                    setTimeout(() => {
                        $('#name, #phone').removeClass('border-success');
                    }, 2000);
                } else {
                    // Donor not found - clear donor_id but auto-fill phone field
                    currentDonorId = null;
                    $('#donor_id').val('');
                    
                    // Auto-fill the phone field with the lookup number
                    const phoneToUse = response.phone || phone;
                    $('#phone').val(phoneToUse);
                    
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
                    
                    // Reset last checked phone since no match found
                    lastCheckedPhone = '';
                }
            },
            error: function(xhr, status, error) {
                $('#lookupSpinner').hide();
                $('#checkDonorBtn').prop('disabled', false);
                
                let errorMessage = 'Error checking donor. Please try again.';
                
                $('#lookupResult').html(`
                    <div class="donor-not-found">
                        <i class="fas fa-exclamation-circle"></i>
                        <div>
                            <strong>Error!</strong><br>
                            ${errorMessage}
                        </div>
                    </div>
                `).show();
                
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

    // Toggle between existing and new donor based on current selection
    $('input[name="donor_type"]').change(function() {
        if ($(this).val() == 'existing') {
            $('#existingDonorSection').slideDown(200);
            $('#newDonorSection').slideUp(200);
            $('#donor_id').prop('required', true);
            $('#name').prop('required', false);
        } else {
            $('#existingDonorSection').slideUp(200);
            $('#newDonorSection').slideDown(200);
            $('#donor_id').prop('required', false);
            $('#name').prop('required', true);
        }
    });
    
    // Auto-fill name and phone when donor is selected
    $('#donor_id').change(function() {
        var selected = $(this).find('option:selected');
        if (selected.val()) {
            $('#name').val(selected.data('name'));
            $('#phone').val(selected.data('phone'));
        }
    });
    
    // Format amount input
    $('#amount').on('input', function() {
        var value = $(this).val().replace(/,/g, '');
        if (!isNaN(value) && value.length > 0) {
            $(this).val(value);
        }
    });
    
    // Warn before leaving if form is dirty
    let formChanged = false;
    $('#donationForm input, #donationForm select, #donationForm textarea').on('change', function() {
        formChanged = true;
    });
    
    $('#donationForm').on('submit', function() {
        formChanged = false;
    });
    
    $(window).on('beforeunload', function() {
        if (formChanged) {
            return 'You have unsaved changes. Are you sure you want to leave?';
        }
    });
});
</script>
@endpush
@endsection