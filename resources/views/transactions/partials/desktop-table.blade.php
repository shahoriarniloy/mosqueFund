<div class="table-responsive">
    <table class="table" style="border-collapse: collapse; width: 100%;">
        <thead>
            <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                <th style="padding: 10px; font-size: 0.7rem; font-weight: 600; color: #64748b;">ID</th>
                <th style="padding: 10px; font-size: 0.7rem; font-weight: 600; color: #64748b;">Donor</th>
                <th style="padding: 10px; font-size: 0.7rem; font-weight: 600; color: #64748b;">Month</th>
                <th style="padding: 10px; font-size: 0.7rem; font-weight: 600; color: #64748b;">Amount</th>
                <th style="padding: 10px; font-size: 0.7rem; font-weight: 600; color: #64748b;">Method</th>
                <th style="padding: 10px; font-size: 0.7rem; font-weight: 600; color: #64748b;">Status</th>
                <th style="padding: 10px; font-size: 0.7rem; font-weight: 600; color: #64748b;">Date</th>
                <th style="padding: 10px; font-size: 0.7rem; font-weight: 600; color: #64748b;">Recorded By</th>
                <th style="padding: 10px; font-size: 0.7rem; font-weight: 600; color: #64748b;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $transaction)
            <tr style="border-bottom: 1px solid #edf2f7;">
                <td style="padding: 10px; font-size: 0.75rem; color: #64748b;">#{{ $transaction->id }}</td>
                <td style="padding: 10px;">
                    <a href="{{ route('donors.show', $transaction->donor) }}" style="color: #2563eb; text-decoration: none; font-size: 0.8rem; font-weight: 500;">
                        {{ $transaction->donor->name }}
                    </a>
                </td>
                <td style="padding: 10px; font-size: 0.75rem;">{{ $transaction->month->name }} {{ $transaction->month->year }}</td>
                <td style="padding: 10px; font-weight: 500; color: #2563eb; font-size: 0.8rem;">৳{{ number_format($transaction->amount) }}</td>
                <td style="padding: 10px;">
                    <span style="background: #eef2ff; color: #4338ca; padding: 4px 8px; border-radius: 20px; font-size: 0.65rem;">
                        {{ ucfirst($transaction->payment_method) }}
                    </span>
                </td>
                <td style="padding: 10px;">
                    <span style="background: {{ $transaction->paid_status == 'paid' ? '#dcfce7' : '#fee2e2' }}; color: {{ $transaction->paid_status == 'paid' ? '#166534' : '#991b1b' }}; padding: 4px 8px; border-radius: 20px; font-size: 0.65rem;">
                        {{ ucfirst($transaction->paid_status) }}
                    </span>
                </td>
                <td style="padding: 10px; font-size: 0.7rem;">{{ $transaction->created_at->format('d M Y') }}</td>
                <td style="padding: 10px; font-size: 0.7rem;">{{ $transaction->user->name }}</td>
                <td style="padding: 10px;">
                    <div class="d-flex gap-1">
                        <a href="{{ route('transactions.show', $transaction) }}" class="btn btn-sm p-0" style="width: 28px; height: 28px; background: #f1f5f9; border-radius: 6px; display: flex; align-items: center; justify-content: center;" title="View">
                            <i class="fas fa-eye" style="font-size: 0.7rem;"></i>
                        </a>
                        <a href="{{ route('transactions.edit', $transaction) }}" class="btn btn-sm p-0" style="width: 28px; height: 28px; background: #f1f5f9; border-radius: 6px; display: flex; align-items: center; justify-content: center;" title="Edit">
                            <i class="fas fa-edit" style="font-size: 0.7rem; color: #2563eb;"></i>
                        </a>
                        @if($transaction->paid_status == 'unpaid')
                        <form action="{{ route('transactions.markAsPaid', $transaction) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm p-0" style="width: 28px; height: 28px; background: #f1f5f9; border: none; border-radius: 6px; display: flex; align-items: center; justify-content: center;" title="Mark Paid">
                                <i class="fas fa-check" style="font-size: 0.7rem; color: #10b981;"></i>
                            </button>
                        </form>
                        @endif
                        {{-- <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm p-0" style="width: 28px; height: 28px; background: #f1f5f9; border: none; border-radius: 6px; display: flex; align-items: center; justify-content: center;" title="Delete" onclick="return confirm('Delete this transaction?')">
                                <i class="fas fa-trash" style="font-size: 0.7rem; color: #ef4444;"></i>
                            </button>
                        </form> --}}
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center py-5">
                    <div style="width: 80px; height: 80px; background: #f1f5f9; border-radius: 30px; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                        <i class="fas fa-exchange-alt fa-3x" style="color: #94a3b8;"></i>
                    </div>
                    <p style="color: #64748b;">No transactions found</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>