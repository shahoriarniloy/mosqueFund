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
                                <select class="form-select tom-select @error('donor_id') is-invalid @enderror" 
                                        id="donor_id" name="donor_id" required
                                        style="border-radius: 40px; padding: 10px 16px; border: 1px solid #e2e8f0; background: white; font-size: 0.95rem;">
                                    <option value="">Search donor by name or phone...</option>
                                    @foreach($donors as $donor)
                                        <option value="{{ $donor->id }}" 
                                                data-monthly="{{ $donor->monthly_amount }}"
                                                data-phone="{{ $donor->phone }}"
                                                {{ old('donor_id', request('donor_id')) == $donor->id ? 'selected' : '' }}>
                                            {{ $donor->name }} - {{ $donor->phone }} (৳{{ number_format($donor->monthly_amount, 2) }}/month)
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
                                        <option value="{{ $month->id }}" 
                                                data-year="{{ $month->year }}"
                                                data-month-name="{{ $month->name }}"
                                                {{ old('month_id') == $month->id ? 'selected' : '' }}>
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
                                           placeholder="Enter amount" 
                                           style="border-radius: 0 40px 40px 0; padding: 10px 16px; border: 1px solid #e2e8f0; background: white; font-size: 0.95rem;" 
                                           required>
                                </div>
                                @error('amount')
                                    <div class="invalid-feedback" style="font-size: 0.8rem; margin-top: 4px;">{{ $message }}</div>
                                @enderror
                                <small class="text-secondary d-block mt-1" style="font-size: 0.7rem;">
                                    <i class="fas fa-info-circle me-1" style="color: #2563eb;"></i>
                                    Enter any amount. Excess over monthly commitment will be recorded as donation
                                </small>
                                
                                <!-- Excess Amount Info -->
                                <div id="excessAmountInfo" class="mt-2 p-2 rounded-3" style="background: #eef2ff; border: 1px solid #c7d2fe; display: none;">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fas fa-gift" style="color: #2563eb;"></i>
                                        <small class="text-primary">
                                            <span id="excessAmount">0.00</span> will be recorded as donation
                                        </small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="payment_method" class="form-label" style="font-size: 0.9rem; font-weight: 500; color: #334155; margin-bottom: 6px;">
                                    Payment Method <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('payment_method') is-invalid @enderror" 
                                        id="payment_method" name="payment_method" required
                                        style="border-radius: 40px; padding: 10px 16px; border: 1px solid #e2e8f0; background: white; font-size: 0.95rem;">
                                    <option value="cash" {{ old('payment_method', 'cash') == 'cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="bkash" {{ old('payment_method') == 'bkash' ? 'selected' : '' }}>bKash</option>
                                    <option value="nagad" {{ old('payment_method') == 'nagad' ? 'selected' : '' }}>Nagad</option>
                                </select>
                                @error('payment_method')
                                    <div class="invalid-feedback" style="font-size: 0.8rem; margin-top: 4px;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Transaction Date -->
                        <div class="row g-3 mb-4">
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
                                    <span id="donationSummary" style="color: #10b981; font-size: 0.8rem; display: none; margin-top: 4px;"></span>
                                </div>
                            </div>
                        </div>
                        
                        <hr style="border-top: 1px dashed #e2e8f0; margin: 24px 0;">
                        
                        <!-- Form Actions -->
                        <div class="d-flex flex-row flex-row justify-content-end gap-2">
                            <a href="{{ route('transactions.index') }}" class="btn w-100 w-sm-auto" style="background: #ef1010;; color: white; border: none; border-radius: 40px; padding: 10px 24px; font-weight: 500; font-size: 0.9rem; display: inline-flex; align-items: center; justify-content: center; gap: 8px;">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn w-100 w-sm-auto" style="background: linear-gradient(145deg, #2563eb, #1d4ed8); color: white; border: none; border-radius: 40px; padding: 10px 24px; font-weight: 500; font-size: 0.9rem; display: inline-flex; align-items: center; justify-content: center; gap: 8px; box-shadow: 0 10px 20px -8px rgba(37,99,235,0.4);">
                                <i class="fas fa-save me-2"></i>Save 
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Tom Select CSS and JS -->
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

<style>
/* Form focus states */
.form-control:focus, .form-select:focus {
    border-color: #2563eb !important;
    box-shadow: 0 0 0 3px rgba(37,99,235,0.15) !important;
    outline: none;
}

/* Tom Select Customization */
.ts-wrapper.form-select {
    padding: 0;
    border: none;
    background: transparent;
}

.ts-control {
    border-radius: 40px !important;
    padding: 8px 16px !important;
    border: 1px solid #e2e8f0 !important;
    min-height: 45px;
    box-shadow: none !important;
}

.ts-control input {
    font-size: 0.95rem !important;
}

.ts-dropdown {
    border-radius: 16px !important;
    border: 1px solid #e2e8f0 !important;
    box-shadow: 0 10px 30px -10px rgba(0,0,0,0.1) !important;
}

.ts-dropdown .option {
    padding: 10px 16px !important;
    font-size: 0.9rem !important;
}

.ts-dropdown .active {
    background: #eef2ff !important;
    color: #2563eb !important;
}

/* Disabled option styling */
select option:disabled {
    background-color: #f1f5f9 !important;
    color: #94a3b8 !important;
    font-style: italic;
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
.btn:not([readonly]):hover {
    transform: translateY(-2px);
    transition: all 0.2s;
}
</style>

@push('scripts')
<script>
    $(document).ready(function() {
        const monthSelect = document.getElementById('month_id');
        let monthlyCommitment = 0;

        // Store original month options
        const originalOptions = [];
        for (let i = 0; i < monthSelect.options.length; i++) {
            originalOptions.push({
                value: monthSelect.options[i].value,
                text: monthSelect.options[i].text
            });
        }

        // Initialize Tom Select for donor
        const donorSelect = new TomSelect('#donor_id', {
            create: false,
            sortField: {
                field: 'text',
                direction: 'asc'
            },
            searchField: ['text'],
            onChange: function(value) {
                if (value) {
                    const selected = donorSelect.options[value];
                    monthlyCommitment = parseFloat(selected.monthly) || 0;

                    $('#selectedMonthlyAmount').text(monthlyCommitment.toFixed(2));
                    $('#monthlyAmountDisplay').slideDown(200);

                    checkPaidMonths(value);
                    calculateExcess();
                } else {
                    monthlyCommitment = 0;
                    $('#selectedMonthlyAmount').text('0.00');
                    $('#monthlyAmountDisplay').slideUp(200);
                    resetMonthOptions();
                    $('#excessAmountInfo').slideUp(200);
                }

                updateSummary();
            }
        });

        // Add custom donor data
        @foreach($donors as $donor)
            if (donorSelect.options['{{ $donor->id }}']) {
                donorSelect.options['{{ $donor->id }}'].monthly = '{{ $donor->monthly_amount }}';
                donorSelect.options['{{ $donor->id }}'].phone = '{{ $donor->phone }}';
            }
        @endforeach

        function checkPaidMonths(donorId) {
            $.ajax({
                url: '{{ route("transactions.getPaidMonths") }}',
                type: 'GET',
                data: { donor_id: donorId },
                success: function(response) {
                    if (response.success) {
                        updateMonthOptions(response.paid_months);
                    }
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                }
            });
        }

        function updateMonthOptions(paidMonths) {
            const currentSelected = $('#month_id').val();
            monthSelect.innerHTML = '';

            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.text = 'Choose a month';
            monthSelect.appendChild(defaultOption);

            originalOptions.forEach(function(opt) {
                if (!opt.value) return;

                const option = document.createElement('option');
                option.value = opt.value;

                const monthId = parseInt(opt.value);

                if (paidMonths.includes(monthId)) {
                    option.text = opt.text + ' (Paid)';
                    option.disabled = true;
                } else {
                    option.text = opt.text;
                }

                if (currentSelected == opt.value && !option.disabled) {
                    option.selected = true;
                }

                monthSelect.appendChild(option);
            });

            updateSummary();
        }

        function resetMonthOptions() {
            const currentSelected = $('#month_id').val();
            monthSelect.innerHTML = '';

            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.text = 'Choose a month';
            monthSelect.appendChild(defaultOption);

            originalOptions.forEach(function(opt) {
                if (!opt.value) return;

                const option = document.createElement('option');
                option.value = opt.value;
                option.text = opt.text;

                if (currentSelected == opt.value) {
                    option.selected = true;
                }

                monthSelect.appendChild(option);
            });

            updateSummary();
        }

        function calculateExcess() {
            const amount = parseFloat($('#amount').val()) || 0;
            
            if (monthlyCommitment > 0 && amount > 0) {
                if (amount > monthlyCommitment) {
                    const excess = amount - monthlyCommitment;
                    $('#excessAmount').text(excess.toFixed(2));
                    $('#excessAmountInfo').slideDown(200);
                    
                    // Update summary with donation info
                    const donorId = $('#donor_id').val();
                    const donor = donorId ? donorSelect.options[donorId] : null;
                    const monthText = $('#month_id option:selected').text().replace(' (Paid)', '');
                    
                    if (donor && donor.text && monthText && monthText !== 'Choose a month') {
                        $('#donationSummary').html(`<i class="fas fa-gift me-1"></i> Includes ৳${excess.toFixed(2)} as donation`).show();
                    }
                } else {
                    $('#excessAmountInfo').slideUp(200);
                    $('#donationSummary').hide();
                }
            } else {
                $('#excessAmountInfo').slideUp(200);
                $('#donationSummary').hide();
            }
        }

        $('#amount').on('input', function() {
            calculateExcess();
            updateSummary();
        });

        $('#month_id').on('change', function() {
            updateSummary();
        });

        function updateSummary() {
            const donorId = $('#donor_id').val();
            const donor = donorId ? donorSelect.options[donorId] : null;
            const monthText = $('#month_id option:selected').text().replace(' (Paid)', '');
            const amount = parseFloat($('#amount').val()) || 0;

            if (donor && donor.text && monthText && monthText !== 'Choose a month' && amount > 0) {
                const donorName = donor.text.split(' - ')[0];
                let summary = 'Transaction for ' + donorName + ' for ' + monthText;
                summary += ' of ৳' + amount.toFixed(2);
                summary += ' - Paid';
                $('#summaryText').text(summary);
            } else {
                $('#summaryText').text('Select a donor, month and enter amount to see summary');
            }
        }

        // Pre-fill old values if any
        @if(old('donor_id'))
            setTimeout(function() {
                donorSelect.setValue('{{ old('donor_id') }}');
            }, 300);
        @endif
    });
</script>
@endpush
@endsection