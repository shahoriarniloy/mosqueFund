@extends('layouts.app')

@section('title', 'Edit Transaction')
@section('page-title', 'Edit Transaction')

@section('page-actions')
    <a href="{{ route('transactions.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to List
    </a>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Edit Transaction #{{ $transaction->id }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('transactions.update', $transaction) }}" method="POST" id="transactionForm">
                    @csrf
                    @method('PUT')
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="donor_id" class="form-label">Select Donor <span class="text-danger">*</span></label>
                            <select class="form-select @error('donor_id') is-invalid @enderror" 
                                    id="donor_id" name="donor_id" required>
                                <option value="">Choose a donor</option>
                                @foreach($donors as $donor)
                                    <option value="{{ $donor->id }}" 
                                            data-monthly="{{ $donor->monthly_amount }}"
                                            {{ old('donor_id', $transaction->donor_id) == $donor->id ? 'selected' : '' }}>
                                        {{ $donor->name }} (৳{{ number_format($donor->monthly_amount, 2) }}/month)
                                    </option>
                                @endforeach
                            </select>
                            @error('donor_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="month_id" class="form-label">Select Month <span class="text-danger">*</span></label>
                            <select class="form-select @error('month_id') is-invalid @enderror" 
                                    id="month_id" name="month_id" required>
                                <option value="">Choose a month</option>
                                @foreach($months as $month)
                                    <option value="{{ $month->id }}" 
                                            {{ old('month_id', $transaction->month_id) == $month->id ? 'selected' : '' }}>
                                        {{ $month->name }} {{ $month->year }}
                                    </option>
                                @endforeach
                            </select>
                            @error('month_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="amount" class="form-label">Amount (৳) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">৳</span>
                                <input type="number" step="0.01" class="form-control @error('amount') is-invalid @enderror" 
                                       id="amount" name="amount" value="{{ old('amount', $transaction->amount) }}" 
                                       placeholder="0.00" required>
                            </div>
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="payment_method" class="form-label">Payment Method <span class="text-danger">*</span></label>
                            <select class="form-select @error('payment_method') is-invalid @enderror" 
                                    id="payment_method" name="payment_method" required>
                                <option value="">Select method</option>
                                <option value="cash" {{ old('payment_method', $transaction->payment_method) == 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="bkash" {{ old('payment_method', $transaction->payment_method) == 'bkash' ? 'selected' : '' }}>bKash</option>
                                <option value="nagad" {{ old('payment_method', $transaction->payment_method) == 'nagad' ? 'selected' : '' }}>Nagad</option>
                            </select>
                            @error('payment_method')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="paid_status" class="form-label">Payment Status <span class="text-danger">*</span></label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="paid_status" id="status_paid" 
                                       value="paid" {{ old('paid_status', $transaction->paid_status) == 'paid' ? 'checked' : '' }} required>
                                <label class="btn btn-outline-success" for="status_paid">
                                    <i class="fas fa-check-circle me-1"></i> Paid
                                </label>
                                
                                <input type="radio" class="btn-check" name="paid_status" id="status_unpaid" 
                                       value="unpaid" {{ old('paid_status', $transaction->paid_status) == 'unpaid' ? 'checked' : '' }} required>
                                <label class="btn btn-outline-danger" for="status_unpaid">
                                    <i class="fas fa-times-circle me-1"></i> Unpaid
                                </label>
                            </div>
                            @error('paid_status')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Record Information</label>
                            <div class="bg-light p-3 rounded">
                                <small class="d-block">
                                    <strong>Created:</strong> {{ $transaction->created_at->format('d M Y, h:i A') }}
                                </small>
                                <small class="d-block">
                                    <strong>By:</strong> {{ $transaction->user->name }}
                                </small>
                                <small class="d-block">
                                    <strong>Last Updated:</strong> {{ $transaction->updated_at->diffForHumans() }}
                                </small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Note:</strong> Changing the donor or month might affect reporting. Please ensure the details are correct.
                    </div>
                    
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('transactions.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Transaction
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection