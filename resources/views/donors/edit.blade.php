@extends('layouts.app')

@section('title', 'Edit Donor')
@section('page-title', 'Edit Donor: ' . $donor->name)

@section('page-actions')
    <a href="{{ route('donors.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to List
    </a>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Edit Donor Information</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('donors.update', $donor) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $donor->name) }}" 
                                   placeholder="Enter donor's full name" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone', $donor->phone) }}" 
                                   placeholder="01XXXXXXXXX" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                  id="address" name="address" rows="3" 
                                  placeholder="Enter full address">{{ old('address', $donor->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="monthly_amount" class="form-label">Monthly Amount (৳) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">৳</span>
                                <input type="number" step="0.01" class="form-control @error('monthly_amount') is-invalid @enderror" 
                                       id="monthly_amount" name="monthly_amount" value="{{ old('monthly_amount', $donor->monthly_amount) }}" 
                                       placeholder="0.00" required>
                            </div>
                            @error('monthly_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                    id="status" name="status" required>
                                <option value="active" {{ (old('status', $donor->status) == 'active') ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ (old('status', $donor->status) == 'inactive') ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Note:</strong> Changing the monthly amount will affect future transactions but won't modify past transactions.
                    </div>
                    
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('donors.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Donor
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection