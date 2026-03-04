@extends('layouts.app')

@section('title', 'Log Details')
@section('page-title', 'Log Entry Details')
@section('page-subtitle', 'View complete audit information')

@section('page-actions')
    <a href="{{ route('donation-logs.index') }}" class="btn" style="background: #f1f5f9; color: #475569; border: none; border-radius: 30px; padding: 8px 18px; font-weight: 500; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 8px;">
        <i class="fas fa-arrow-left"></i> Back to Logs
    </a>
@endsection

@section('content')
<div class="container-fluid px-2 px-sm-3">
    <div class="row justify-content-center g-3">
        <div class="col-12 col-lg-8">
            <!-- Main Log Card -->
            <div class="content-card" style="border-radius: 24px; overflow: hidden; background: white; box-shadow: 0 20px 40px -12px rgba(0,20,40,0.12); border: 1px solid rgba(226, 232, 240, 0.6);">
                <!-- Header -->
                <div class="px-4 py-3" style="background: linear-gradient(145deg, 
                    {{ $donationLog->action == 'created' ? '#dbeafe, #bfdbfe' : ($donationLog->action == 'updated' ? '#fef3c7, #fde68a' : '#fee2e2, #fecaca') }}); 
                    border-bottom: 1px solid rgba(226, 232, 240, 0.6);">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width: 48px; height: 48px; background: white; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-{{ $donationLog->action == 'created' ? 'plus-circle' : ($donationLog->action == 'updated' ? 'edit' : 'trash') }} fa-2x" 
                               style="color: {{ $donationLog->action == 'created' ? '#2563eb' : ($donationLog->action == 'updated' ? '#d97706' : '#dc2626') }};"></i>
                        </div>
                        <div>
                            <h5 class="mb-0" style="font-weight: 600; color: #1e293b;">
                                {{ ucfirst($donationLog->action) }} Action
                            </h5>
                            <small class="text-secondary">{{ $donationLog->created_at->format('F d, Y \a\t h:i:s A') }}</small>
                        </div>
                    </div>
                </div>
                
                <div class="p-4">
                    <!-- Donation Information -->
                    <div class="mb-4">
                        <h6 class="mb-2" style="font-size: 0.8rem; font-weight: 600; color: #64748b;">
                            <i class="fas fa-hand-holding-heart me-2" style="color: #2563eb;"></i>
                            Donation Details
                        </h6>
                        <div class="p-3 rounded-3" style="background: #f8fafc;">
                            @if($donationLog->donation)
                                <div class="d-flex align-items-center gap-3">
                                    <div style="width: 44px; height: 44px; background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                        <span class="text-white fw-bold fs-5">{{ substr($donationLog->donation->name, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <h5 class="mb-1" style="font-weight: 600;">{{ $donationLog->donation->name }}</h5>
                                        <p class="mb-0" style="font-size: 0.8rem; color: #64748b;">
                                            ID: #{{ $donationLog->donation->id }} | 
                                            Phone: {{ $donationLog->donation->phone ?? 'N/A' }}
                                        </p>
                                    </div>
                                </div>
                            @else
                                <p class="text-danger mb-0">Donation has been deleted</p>
                            @endif
                        </div>
                    </div>
                    
                    <!-- User Information -->
                    <div class="mb-4">
                        <h6 class="mb-2" style="font-size: 0.8rem; font-weight: 600; color: #64748b;">
                            <i class="fas fa-user me-2" style="color: #2563eb;"></i>
                            Performed By
                        </h6>
                        <div class="p-3 rounded-3" style="background: #f8fafc;">
                            <div class="d-flex align-items-center gap-3">
                                <div style="width: 44px; height: 44px; background: linear-gradient(135deg, #3b82f6, #8b5cf6); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                    <span class="text-white fw-bold fs-5">{{ substr($donationLog->user->name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <h5 class="mb-1" style="font-weight: 600;">{{ $donationLog->user->name }}</h5>
                                    <p class="mb-0" style="font-size: 0.8rem; color: #64748b;">
                                        ID: #{{ $donationLog->user->id }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Change Details -->
                    @if($donationLog->field_name)
                    <div class="mb-4">
                        <h6 class="mb-2" style="font-size: 0.8rem; font-weight: 600; color: #64748b;">
                            <i class="fas fa-exchange-alt me-2" style="color: #2563eb;"></i>
                            Change Details
                        </h6>
                        <div class="p-3 rounded-3" style="background: #f8fafc;">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="p-2 rounded-2" style="background: white;">
                                        <small class="text-secondary d-block" style="font-size: 0.6rem;">FIELD</small>
                                        <span style="font-weight: 500; font-size: 0.9rem;">{{ ucfirst(str_replace('_', ' ', $donationLog->field_name)) }}</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-2 rounded-2" style="background: #fee2e2;">
                                        <small class="text-danger d-block" style="font-size: 0.6rem;">OLD VALUE</small>
                                        @if($donationLog->field_name == 'amount')
                                            <span style="font-weight: 600; color: #dc2626; font-size: 1rem;">৳{{ number_format($donationLog->old_value, 2) }}</span>
                                        @elseif($donationLog->field_name == 'paid_status')
                                            <span class="badge" style="background: {{ $donationLog->old_value == 'paid' ? '#dcfce7' : '#fee2e2' }}; color: {{ $donationLog->old_value == 'paid' ? '#166534' : '#991b1b' }}; padding: 4px 8px;">{{ ucfirst($donationLog->old_value) }}</span>
                                        @else
                                            <span style="font-weight: 500;">{{ $donationLog->old_value ?: '<em>Empty</em>' }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-2 rounded-2" style="background: #dcfce7;">
                                        <small class="text-success d-block" style="font-size: 0.6rem;">NEW VALUE</small>
                                        @if($donationLog->field_name == 'amount')
                                            <span style="font-weight: 600; color: #10b981; font-size: 1rem;">৳{{ number_format($donationLog->new_value, 2) }}</span>
                                        @elseif($donationLog->field_name == 'paid_status')
                                            <span class="badge" style="background: {{ $donationLog->new_value == 'paid' ? '#dcfce7' : '#fee2e2' }}; color: {{ $donationLog->new_value == 'paid' ? '#166534' : '#991b1b' }}; padding: 4px 8px;">{{ ucfirst($donationLog->new_value) }}</span>
                                        @else
                                            <span style="font-weight: 500;">{{ $donationLog->new_value ?: '<em>Empty</em>' }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Complete Snapshot -->
                    @if($donationLog->donation_snapshot)
                    <div class="mb-4">
                        <h6 class="mb-2" style="font-size: 0.8rem; font-weight: 600; color: #64748b;">
                            <i class="fas fa-camera me-2" style="color: #2563eb;"></i>
                            Complete Snapshot at Time of Change
                        </h6>
                        <div class="p-3 rounded-3" style="background: #f8fafc; max-height: 300px; overflow-y: auto;">
                            <pre style="font-size: 0.7rem; margin: 0;">{{ json_encode($donationLog->donation_snapshot, JSON_PRETTY_PRINT) }}</pre>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Metadata -->
                    <div>
                        <h6 class="mb-2" style="font-size: 0.8rem; font-weight: 600; color: #64748b;">
                            <i class="fas fa-info-circle me-2" style="color: #2563eb;"></i>
                            Metadata
                        </h6>
                        <div class="p-3 rounded-3" style="background: #f8fafc;">
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <small class="text-secondary d-block">IP Address</small>
                                    <span style="font-size: 0.8rem; font-family: monospace;">{{ $donationLog->ip_address ?: 'N/A' }}</span>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-secondary d-block">User Agent</small>
                                    <span style="font-size: 0.7rem;">{{ $donationLog->user_agent ?: 'N/A' }}</span>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-secondary d-block">Log ID</small>
                                    <span style="font-size: 0.7rem;">#{{ $donationLog->id }}</span>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-secondary d-block">Donation ID</small>
                                    <span style="font-size: 0.7rem;">#{{ $donationLog->donation_id }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection