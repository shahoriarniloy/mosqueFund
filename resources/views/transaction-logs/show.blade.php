@extends('layouts.app')

@section('title', 'Log Details')
@section('page-title', 'Transaction Log Entry')
@section('page-subtitle', 'View complete audit information')

@section('page-actions')
    <a href="{{ route('transaction-logs.index') }}?{{ http_build_query(request()->except('page')) }}" class="btn" style="background: #f1f5f9; color: #475569; border: none; border-radius: 30px; padding: 8px 18px; font-weight: 500; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 8px;">
        <i class="fas fa-arrow-left"></i> Back to Logs
    </a>
    <a href="{{ route('transactions.show', $transactionLog->transaction) }}" class="btn" style="background: #2563eb; color: white; border: none; border-radius: 30px; padding: 8px 18px; font-weight: 500; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 8px;" {{ !$transactionLog->transaction ? 'disabled' : '' }}>
        <i class="fas fa-exchange-alt me-2"></i> View Transaction
    </a>
@endsection

@section('content')
<div class="container-fluid px-2 px-sm-3">
    <div class="row justify-content-center g-3">
        <div class="col-12 col-lg-8">
            <!-- Main Log Card -->
            <div class="content-card" style="border-radius: 24px; overflow: hidden; background: white; box-shadow: 0 20px 40px -12px rgba(0,20,40,0.12); border: 1px solid rgba(226, 232, 240, 0.6);">
                <!-- Header with Action Color -->
                <div class="px-4 py-3" style="background: linear-gradient(145deg, 
                    {{ $transactionLog->action == 'created' ? '#dbeafe, #bfdbfe' : ($transactionLog->action == 'updated' ? '#fef3c7, #fde68a' : '#fee2e2, #fecaca') }}); 
                    border-bottom: 1px solid rgba(226, 232, 240, 0.6);">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width: 48px; height: 48px; background: white; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-{{ $transactionLog->action == 'created' ? 'plus-circle' : ($transactionLog->action == 'updated' ? 'edit' : 'trash') }} fa-2x" 
                               style="color: {{ $transactionLog->action == 'created' ? '#2563eb' : ($transactionLog->action == 'updated' ? '#d97706' : '#dc2626') }};"></i>
                        </div>
                        <div>
                            <h5 class="mb-0" style="font-weight: 600; color: #1e293b;">
                                {{ ucfirst($transactionLog->action) }} Action
                            </h5>
                            <div class="d-flex gap-2 mt-1">
                                <small class="text-secondary">{{ $transactionLog->created_at->format('F d, Y') }}</small>
                                <small class="text-secondary">•</small>
                                <small class="text-secondary">{{ $transactionLog->created_at->format('h:i:s A') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="p-4">
                    <!-- Transaction Information -->
                    <div class="mb-4">
                        <h6 class="mb-2" style="font-size: 0.8rem; font-weight: 600; color: #64748b;">
                            <i class="fas fa-exchange-alt me-2" style="color: #2563eb;"></i>
                            Transaction Details
                        </h6>
                        <div class="p-3 rounded-3" style="background: #f8fafc;">
                            @if($transactionLog->transaction)
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center gap-2">
                                            <div style="width: 36px; height: 36px; background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-user text-white" style="font-size: 0.8rem;"></i>
                                            </div>
                                            <div>
                                                <small class="text-secondary d-block" style="font-size: 0.55rem;">DONOR</small>
                                                <span style="font-weight: 500;">{{ $transactionLog->transaction->donor->name ?? 'N/A' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center gap-2">
                                            <div style="width: 36px; height: 36px; background: linear-gradient(135deg, #10b981, #059669); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-calendar text-white" style="font-size: 0.8rem;"></i>
                                            </div>
                                            <div>
                                                <small class="text-secondary d-block" style="font-size: 0.55rem;">MONTH</small>
                                                <span style="font-weight: 500;">{{ $transactionLog->transaction->month->name ?? 'N/A' }} {{ $transactionLog->transaction->month->year ?? '' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center gap-2">
                                            <div style="width: 36px; height: 36px; background: linear-gradient(135deg, #f59e0b, #d97706); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-coins text-white" style="font-size: 0.8rem;"></i>
                                            </div>
                                            <div>
                                                <small class="text-secondary d-block" style="font-size: 0.55rem;">AMOUNT</small>
                                                <span style="font-weight: 600; color: #2563eb;">৳{{ number_format($transactionLog->transaction->amount, 2) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center gap-2">
                                            <div style="width: 36px; height: 36px; background: linear-gradient(135deg, #8b5cf6, #7c3aed); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-credit-card text-white" style="font-size: 0.8rem;"></i>
                                            </div>
                                            <div>
                                                <small class="text-secondary d-block" style="font-size: 0.55rem;">PAYMENT METHOD</small>
                                                <span>{{ ucfirst($transactionLog->transaction->payment_method) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <p class="text-danger mb-0">Transaction has been deleted</p>
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
                                <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #3b82f6, #8b5cf6); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                    <span class="text-white fw-bold fs-5">{{ substr($transactionLog->user->name ?? 'U', 0, 1) }}</span>
                                </div>
                                <div>
                                    <h5 class="mb-1" style="font-weight: 600;">{{ $transactionLog->user->name ?? 'Unknown User' }}</h5>
                                    <p class="mb-0" style="font-size: 0.8rem; color: #64748b;">
                                        User ID: #{{ $transactionLog->user->id ?? 'N/A' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Change Details -->
                    @if($transactionLog->field_name)
                    <div class="mb-4">
                        <h6 class="mb-2" style="font-size: 0.8rem; font-weight: 600; color: #64748b;">
                            <i class="fas fa-exchange-alt me-2" style="color: #2563eb;"></i>
                            Change Details
                        </h6>
                        <div class="p-3 rounded-3" style="background: #f8fafc;">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="p-2 rounded-2" style="background: white; border: 1px solid #e2e8f0;">
                                        <small class="text-secondary d-block" style="font-size: 0.55rem;">FIELD</small>
                                        <span style="font-weight: 500; font-size: 0.9rem;">{{ ucfirst(str_replace('_', ' ', $transactionLog->field_name)) }}</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-2 rounded-2" style="background: #fee2e2; border: 1px solid #fecaca;">
                                        <small class="text-danger d-block" style="font-size: 0.55rem;">OLD VALUE</small>
                                        @if($transactionLog->field_name == 'amount')
                                            <span style="font-weight: 600; color: #dc2626; font-size: 1rem;">৳{{ number_format($transactionLog->old_value, 2) }}</span>
                                        @elseif($transactionLog->field_name == 'paid_status')
                                            <span class="badge" style="background: {{ $transactionLog->old_value == 'paid' ? '#dcfce7' : '#fee2e2' }}; color: {{ $transactionLog->old_value == 'paid' ? '#166534' : '#991b1b' }}; padding: 4px 8px;">{{ ucfirst($transactionLog->old_value) }}</span>
                                        @elseif($transactionLog->field_name == 'payment_method')
                                            <span>{{ ucfirst($transactionLog->old_value) }}</span>
                                        @else
                                            <span style="font-weight: 500;">{{ $transactionLog->old_value ?: '<em>Empty</em>' }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-2 rounded-2" style="background: #dcfce7; border: 1px solid #bbf7d0;">
                                        <small class="text-success d-block" style="font-size: 0.55rem;">NEW VALUE</small>
                                        @if($transactionLog->field_name == 'amount')
                                            <span style="font-weight: 600; color: #10b981; font-size: 1rem;">৳{{ number_format($transactionLog->new_value, 2) }}</span>
                                        @elseif($transactionLog->field_name == 'paid_status')
                                            <span class="badge" style="background: {{ $transactionLog->new_value == 'paid' ? '#dcfce7' : '#fee2e2' }}; color: {{ $transactionLog->new_value == 'paid' ? '#166534' : '#991b1b' }}; padding: 4px 8px;">{{ ucfirst($transactionLog->new_value) }}</span>
                                        @elseif($transactionLog->field_name == 'payment_method')
                                            <span>{{ ucfirst($transactionLog->new_value) }}</span>
                                        @else
                                            <span style="font-weight: 500;">{{ $transactionLog->new_value ?: '<em>Empty</em>' }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Complete Snapshot -->
                    @if($transactionLog->transaction_snapshot)
                    <div class="mb-4">
                        <h6 class="mb-2" style="font-size: 0.8rem; font-weight: 600; color: #64748b;">
                            <i class="fas fa-camera me-2" style="color: #2563eb;"></i>
                            Complete Snapshot at Time of Change
                        </h6>
                        <div class="p-3 rounded-3" style="background: #f8fafc; max-height: 250px; overflow-y: auto;">
                            <pre style="font-size: 0.7rem; margin: 0; font-family: monospace;">{{ json_encode($transactionLog->transaction_snapshot, JSON_PRETTY_PRINT) }}</pre>
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
                                <div class="col-md-4">
                                    <small class="text-secondary d-block" style="font-size: 0.55rem;">LOG ID</small>
                                    <span style="font-size: 0.8rem; font-family: monospace;">#{{ $transactionLog->id }}</span>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-secondary d-block" style="font-size: 0.55rem;">IP ADDRESS</small>
                                    <span style="font-size: 0.8rem; font-family: monospace;">{{ $transactionLog->ip_address ?: 'N/A' }}</span>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-secondary d-block" style="font-size: 0.55rem;">USER AGENT</small>
                                    <span style="font-size: 0.65rem;">{{ Str::limit($transactionLog->user_agent, 30) ?: 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
pre {
    white-space: pre-wrap;
    word-wrap: break-word;
}
</style>
@endsection