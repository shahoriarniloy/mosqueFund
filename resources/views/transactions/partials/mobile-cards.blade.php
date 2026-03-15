@forelse($transactions as $transaction)
<div class="mobile-transaction-card mb-2 p-2" style="background: white; border-radius: 12px; border: 1px solid #edf2f7;">
    <!-- Header Row -->
    <div class="d-flex justify-content-between align-items-center mb-1">
        <div class="d-flex align-items-center gap-2">
            <span class="fw-bold" style="color: #2563eb; font-size: 0.7rem;">#{{ $transaction->id }}</span>
            <span style="font-size: 0.85rem; font-weight: 500;">{{ $transaction->donor->name }}</span>
        </div>
        <span class="badge" style="background: {{ $transaction->paid_status == 'paid' ? '#dcfce7' : '#fee2e2' }}; color: {{ $transaction->paid_status == 'paid' ? '#166534' : '#991b1b' }}; padding: 4px 8px; border-radius: 20px; font-size: 0.65rem;">
            <i class="fas fa-{{ $transaction->paid_status == 'paid' ? 'check-circle' : 'clock' }} me-1"></i>
            {{ ucfirst($transaction->paid_status) }}
        </span>
    </div>
    
    <!-- Details Row -->
    <div class="row g-1 mb-1">
        <div class="col-6">
            <small class="text-muted d-block" style="font-size: 0.6rem;">Month</small>
            <span style="font-size: 0.8rem;">{{ $transaction->month->name }} {{ $transaction->month->year }}</span>
        </div>
        <div class="col-6">
            <small class="text-muted d-block" style="font-size: 0.6rem;">Amount</small>
            <span style="font-size: 0.9rem; font-weight: 600; color: #2563eb;">৳{{ number_format($transaction->amount) }}</span>
        </div>
        <div class="col-6">
            <small class="text-muted d-block" style="font-size: 0.6rem;">Method</small>
            <span class="badge" style="background: #eef2ff; color: #4338ca; padding: 4px 8px; border-radius: 16px; font-size: 0.65rem;">
                {{ ucfirst($transaction->payment_method) }}
            </span>
        </div>
        <div class="col-6">
            <small class="text-muted d-block" style="font-size: 0.6rem;">Date</small>
            <span style="font-size: 0.75rem;">{{ $transaction->created_at->format('d M Y') }}</span>
        </div>
        <div class="col-12">
            <small class="text-muted d-block" style="font-size: 0.6rem;">Recorded By</small>
            <span style="font-size: 0.75rem;">{{ $transaction->user->name }}</span>
        </div>
    </div>
    
    <!-- Action Buttons -->
    <div class="d-flex gap-1 mt-2 pt-1" style="border-top: 1px solid #edf2f7;">
        <a href="{{ route('transactions.show', $transaction) }}" class="btn btn-sm flex-fill" style="background: #f8fafc; color: #475569; border-radius: 20px; padding: 6px 0; border: 1px solid #e2e8f0; font-size: 0.7rem;">
            <i class="fas fa-eye me-1"></i> View
        </a>
        <a href="{{ route('transactions.edit', $transaction) }}" class="btn btn-sm flex-fill" style="background: #f8fafc; color: #2563eb; border-radius: 20px; padding: 6px 0; border: 1px solid #e2e8f0; font-size: 0.7rem;">
            <i class="fas fa-edit me-1"></i> Edit
        </a>
        @if($transaction->paid_status == 'unpaid')
        <form action="{{ route('transactions.markAsPaid', $transaction) }}" method="POST" class="flex-fill">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn btn-sm w-100" style="background: #f8fafc; color: #10b981; border-radius: 20px; padding: 6px 0; border: 1px solid #e2e8f0; font-size: 0.7rem;" onclick="return confirm('Mark as paid?')">
                <i class="fas fa-check me-1"></i> Pay
            </button>
        </form>
        @endif
        {{-- <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" class="flex-fill">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm w-100" style="background: #f8fafc; color: #ef4444; border-radius: 20px; padding: 6px 0; border: 1px solid #e2e8f0; font-size: 0.7rem;" onclick="return confirm('Delete this transaction?')">
                <i class="fas fa-trash me-1"></i> Delete
            </button>
        </form> --}}
    </div>
</div>
@empty
<div class="text-center py-4">
    <div style="width: 48px; height: 48px; background: #f1f5f9; border-radius: 16px; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px;">
        <i class="fas fa-exchange-alt" style="color: #94a3b8; font-size: 1.2rem;"></i>
    </div>
    <p style="color: #64748b; font-size: 0.8rem;">No transactions found</p>
</div>
@endforelse