@extends('layouts.app')

@section('title', 'Add New Transaction')
@section('page-title', 'Add New Transaction')

@section('page-actions')
    <a href="{{ route('transactions.index') }}" class="btn" style="background: #f1f5f9; color: #475569; border: none; border-radius: 30px; padding: 8px 18px; font-weight: 500; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 8px;">
        <i class="fas fa-arrow-left"></i> Back to List
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
                        <i class="fas fa-exchange-alt me-2" style="color: #2563eb;"></i>
                        Transaction Information
                    </h5>
                </div>
                
                <div class="p-4">
                    <form action="{{ route('transactions.store') }}" method="POST" id="transactionForm">
                        @csrf
                        
                        <!-- Hidden field for paid_status - always paid -->
                        <input type="hidden" name="paid_status" value="paid">
                        
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="donor_id" class="form-label" style="font-size: 0.9rem; font-weight: 500; color: #334155; margin-bottom: 6px;">
                                    Select Donor <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('donor_id') is-invalid @enderror" 
                                        id="donor_id" name="donor_id" required
                                        style="border-radius: 40px; padding: 10px 16px; border: 1px solid #e2e8f0; background: white; font-size: 0.95rem;">
                                    <option value="">Choose a donor</option>
                                    @foreach($donors as $donor)
                                        <option value="{{ $donor->id }}" 
                                                data-monthly="{{ $donor->monthly_amount }}"
                                                {{ old('donor_id', request('donor_id')) == $donor->id ? 'selected' : '' }}>
                                            {{ $donor->name }} (৳{{ number_format($donor->monthly_amount, 2) }}/month)
                                        </option>
                                    @endforeach
                                </select>
                                @error('donor_id')
                                    <div class="invalid-feedback" style="font-size: 0.8rem; margin-top: 4px;">{{ $message }}</div>
                                @enderror
                                
                                <!-- Monthly Amount Display -->
                                <div id="monthlyAmountDisplay" class="mt-2 p-2 rounded-3" style="background: #f8fafc; border: 1px solid #e2e8f0; display: none;">
                                    <small class="text-secondary d-block" style="font-size: 0.65rem;">MONTHLY COMMITMENT</small>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span class="fw-bold" style="color: #2563eb; font-size: 1rem;">৳<span id="selectedMonthlyAmount">0.00</span></span>
                                        <span class="badge" style="background: #dbeafe; color: #1e40af; font-size: 0.6rem;">Auto-filled</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="month_id" class="form-label" style="font-size: 0.9rem; font-weight: 500; color: #334155; margin-bottom: 6px;">
                                    Select Month <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('month_id') is-invalid @enderror" 
                                        id="month_id" name="month_id" required
                                        style="border-radius: 40px; padding: 10px 16px; border: 1px solid #e2e8f0; background: white; font-size: 0.95rem;">
                                    <option value="">Choose a month</option>
                                    @foreach($months as $month)
                                        <option value="{{ $month->id }}" {{ old('month_id') == $month->id ? 'selected' : '' }}>
                                            {{ $month->name }} {{ $month->year }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('month_id')
                                    <div class="invalid-feedback" style="font-size: 0.8rem; margin-top: 4px;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="amount" class="form-label" style="font-size: 0.9rem; font-weight: 500; color: #334155; margin-bottom: 6px;">
                                    Amount (৳) <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 40px 0 0 40px; border-right: none; color: #475569;">৳</span>
                                    <input type="number" step="0.01" class="form-control @error('amount') is-invalid @enderror" 
                                           id="amount" name="amount" value="{{ old('amount') }}" 
                                           placeholder="Amount will auto-fill" 
                                           style="border-radius: 0 40px 40px 0; padding: 10px 16px; border: 1px solid #e2e8f0; background: #f1f5f9; font-size: 0.95rem; color: #1e293b;" 
                                           readonly>
                                </div>
                                @error('amount')
                                    <div class="invalid-feedback" style="font-size: 0.8rem; margin-top: 4px;">{{ $message }}</div>
                                @enderror
                                <small class="text-secondary d-block mt-1" style="font-size: 0.7rem;">
                                    <i class="fas fa-info-circle me-1" style="color: #2563eb;"></i>
                                    Amount is fixed based on donor's monthly commitment
                                </small>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="payment_method" class="form-label" style="font-size: 0.9rem; font-weight: 500; color: #334155; margin-bottom: 6px;">
                                    Payment Method <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('payment_method') is-invalid @enderror" 
                                        id="payment_method" name="payment_method" required
                                        style="border-radius: 40px; padding: 10px 16px; border: 1px solid #e2e8f0; background: white; font-size: 0.95rem;">
                                    <option value="">Select method</option>
                                    <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="bkash" {{ old('payment_method') == 'bkash' ? 'selected' : '' }}>bKash</option>
                                    <option value="nagad" {{ old('payment_method') == 'nagad' ? 'selected' : '' }}>Nagad</option>
                                </select>
                                @error('payment_method')
                                    <div class="invalid-feedback" style="font-size: 0.8rem; margin-top: 4px;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Payment Status Display (Always Paid) -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label" style="font-size: 0.9rem; font-weight: 500; color: #334155; margin-bottom: 6px;">
                                    Payment Status
                                </label>
                                <div class="form-control" style="background: #f1f5f9; border-radius: 40px; padding: 10px 16px; border: 1px solid #e2e8f0; color: #10b981; font-weight: 600; display: flex; align-items: center; gap: 8px;" readonly>
                                    <i class="fas fa-check-circle"></i> Paid
                                </div>
                                <small class="text-secondary d-block mt-1" style="font-size: 0.7rem;">
                                    <i class="fas fa-info-circle me-1" style="color: #2563eb;"></i>
                                    Transactions are always recorded as paid
                                </small>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label" style="font-size: 0.9rem; font-weight: 500; color: #334155; margin-bottom: 6px;">
                                    Transaction Date
                                </label>
                                <div class="form-control" style="background: #f8fafc; border-radius: 40px; padding: 10px 16px; border: 1px solid #e2e8f0; color: #475569;" readonly>
                                    {{ now()->format('d M Y, h:i A') }}
                                </div>
                                <small class="text-muted" style="font-size: 0.75rem;">Will be recorded automatically</small>
                            </div>
                        </div>
                        
                        <!-- Modern Info Alert -->
                        <div class="mb-4 p-3" style="background: linear-gradient(145deg, #eef2ff, #e0e7ff); border-radius: 20px; border: 1px solid #c7d2fe;">
                            <div class="d-flex align-items-center gap-3">
                                <div style="width: 40px; height: 40px; background: rgba(37,99,235,0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-info-circle" style="color: #2563eb; font-size: 1.2rem;"></i>
                                </div>
                                <div>
                                    <strong style="color: #1e40af; font-size: 0.9rem;">Transaction Summary:</strong><br>
                                    <span id="summaryText" style="color: #1e293b; font-size: 0.85rem;">Select a donor and month</span>
                                </div>
                            </div>
                        </div>
                        
                        <hr style="border-top: 1px dashed #e2e8f0; margin: 24px 0;">
                        
                        <!-- Form Actions -->
                        <div class="d-flex flex-column flex-sm-row justify-content-end gap-2">
                            <a href="{{ route('transactions.index') }}" class="btn w-100 w-sm-auto" style="background: #f1f5f9; color: #475569; border: none; border-radius: 40px; padding: 10px 24px; font-weight: 500; font-size: 0.9rem; display: inline-flex; align-items: center; justify-content: center; gap: 8px;">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn w-100 w-sm-auto" style="background: linear-gradient(145deg, #2563eb, #1d4ed8); color: white; border: none; border-radius: 40px; padding: 10px 24px; font-weight: 500; font-size: 0.9rem; display: inline-flex; align-items: center; justify-content: center; gap: 8px; box-shadow: 0 10px 20px -8px rgba(37,99,235,0.4);">
                                <i class="fas fa-save me-2"></i>Save Transaction
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
.btn:not([readonly]):hover {
    transform: translateY(-2px);
    transition: all 0.2s;
}

.btn-check + .btn:hover {
    background: #eef2ff !important;
    border-color: #2563eb !important;
    color: #1e293b !important;
}

.btn-check:checked + .btn:hover {
    filter: brightness(1.1) !important;
}

/* Read-only amount field styling */
#amount[readonly] {
    background: #f1f5f9;
    color: #1e293b;
    font-weight: 500;
}
</style>

@push('scripts')
<script>
    $(document).ready(function() {
        // Update amount when donor is selected
        $('#donor_id').on('change', function() {
            var selected = $(this).find('option:selected');
            var monthlyAmount = selected.data('monthly') || 0;
            
            console.log('Donor changed:', selected.val(), 'Monthly amount:', monthlyAmount); // Debug log
            
            if (selected.val()) {
                // Set amount field to monthly amount
                $('#amount').val(monthlyAmount);
                $('#selectedMonthlyAmount').text(parseFloat(monthlyAmount).toFixed(2));
                $('#monthlyAmountDisplay').slideDown(200);
                
                // Also set the old amount value for form submission
                $('#amount').val(monthlyAmount);
            } else {
                $('#amount').val('');
                $('#monthlyAmountDisplay').slideUp(200);
            }
            
            updateSummary();
        });
        
        $('#month_id').on('change', function() {
            updateSummary();
        });
        
        function updateSummary() {
            var donor = $('#donor_id option:selected').text();
            var month = $('#month_id option:selected').text();
            var amount = $('#amount').val();
            
            if (donor && donor !== 'Choose a donor' && month && month !== 'Choose a month') {
                var donorName = donor.split(' (')[0];
                var summary = 'Transaction for ' + donorName + ' for ' + month;
                if (amount) {
                    summary += ' of ৳' + parseFloat(amount).toFixed(2);
                }
                summary += ' - Paid';
                $('#summaryText').text(summary);
            } else {
                $('#summaryText').text('Select a donor and month to see summary');
            }
        }
        
        // Trigger change on page load if donor is pre-selected (for edit mode)
        if ($('#donor_id').val()) {
            console.log('Pre-selected donor found, triggering change');
            $('#donor_id').trigger('change');
        }
        
        // For debugging - check if amount field exists
        console.log('Amount field exists:', $('#amount').length > 0);
        console.log('Donor dropdown exists:', $('#donor_id').length > 0);
    });
</script>
@endpush
@endsection