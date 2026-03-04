@extends('layouts.app')

@section('title', 'Transactions Management')

@section('content')
<div class="container-fluid px-0 px-sm-3">
    <div class="row g-0 g-sm-3">
        <div class="col-12">
            <!-- Modern Card with subtle styling -->
            <div class="" style="border-radius: 0; overflow: hidden;">
                <!-- Card Header -->
                <div class="px-3 px-sm-4 py-2 py-sm-3" style="background: linear-gradient(145deg, #fafcff, #ffffff); border-bottom: 1px solid rgba(226, 232, 240, 0.6);">
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 gap-sm-3">
                        <h5 class="mb-0" style="font-family: 'Space Grotesk', sans-serif; font-weight: 600; color: #0b1e33; font-size: 1rem sm:font-size-1.3rem;">
                            <i class="fas fa-exchange-alt me-2" style="color: #2563eb;"></i>
                            Transactions
                        </h5>
                        <a href="{{ route('transactions.create') }}" class="btn w-100 w-sm-auto" style="background: linear-gradient(145deg, #2563eb, #1d4ed8); color: white; border: none; border-radius: 40px; padding: 8px 18px; font-weight: 500; font-size: 0.9rem; box-shadow: 0 10px 20px -8px rgba(37,99,235,0.4);">
                            <i class="fas fa-plus me-2"></i>Add
                        </a>
                    </div>
                </div>

                <div class="p-2 p-sm-4">
                    <!-- Statistics Cards - Minimal padding on mobile -->
                    <div class="row g-1 g-sm-3 mb-3 mb-sm-4">
                        <!-- Total Amount -->
                        <div class="col-6 col-md-3">
                            <div class="stat-compact p-2 p-sm-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px; box-shadow: 0 8px 16px -8px rgba(102,126,234,0.3);">
                                <div class="d-flex align-items-center gap-1 gap-sm-3">
                                    <div style="width: 32px; height: 32px; background: rgba(255,255,255,0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-coins" style="font-size: 0.9rem; color: white;"></i>
                                    </div>
                                    <div>
                                        <p class="mb-0 text-white opacity-75" style="font-size: 0.55rem; line-height: 1.2;">Total</p>
                                        <h6 class="mb-0 text-white" style="font-size: 0.9rem; font-weight: 600; line-height: 1.2;">৳{{ number_format($totalAmount ?? 0) }}</h6>
                                        <span class="text-white-50" style="font-size: 0.45rem;">Filtered</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Paid -->
                        <div class="col-6 col-md-3">
                            <div class="stat-compact p-2 p-sm-3" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); border-radius: 12px; box-shadow: 0 8px 16px -8px rgba(17,153,142,0.3);">
                                <div class="d-flex align-items-center gap-1 gap-sm-3">
                                    <div style="width: 32px; height: 32px; background: rgba(255,255,255,0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-check-circle" style="font-size: 0.9rem; color: white;"></i>
                                    </div>
                                    <div>
                                        <p class="mb-0 text-white opacity-75" style="font-size: 0.55rem;">Paid</p>
                                        <h6 class="mb-0 text-white" style="font-size: 0.9rem; font-weight: 600;">{{ $paidCount ?? 0 }}</h6>
                                        <span class="text-white-50" style="font-size: 0.45rem;">Trans</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Unpaid -->
                        <div class="col-6 col-md-3">
                            <div class="stat-compact p-2 p-sm-3" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 12px; box-shadow: 0 8px 16px -8px rgba(240,147,251,0.3);">
                                <div class="d-flex align-items-center gap-1 gap-sm-3">
                                    <div style="width: 32px; height: 32px; background: rgba(255,255,255,0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-clock" style="font-size: 0.9rem; color: white;"></i>
                                    </div>
                                    <div>
                                        <p class="mb-0 text-white opacity-75" style="font-size: 0.55rem;">Unpaid</p>
                                        <h6 class="mb-0 text-white" style="font-size: 0.9rem; font-weight: 600;">{{ $unpaidCount ?? 0 }}</h6>
                                        <span class="text-white-50" style="font-size: 0.45rem;">Trans</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Collection Rate -->
                        <div class="col-6 col-md-3">
                            <div class="stat-compact p-2 p-sm-3" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 12px; box-shadow: 0 8px 16px -8px rgba(79,172,254,0.3);">
                                <div class="d-flex align-items-center gap-1 gap-sm-3">
                                    <div style="width: 32px; height: 32px; background: rgba(255,255,255,0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-chart-pie" style="font-size: 0.9rem; color: white;"></i>
                                    </div>
                                    <div>
                                        @php
                                            $totalTransactions = ($paidCount ?? 0) + ($unpaidCount ?? 0);
                                            $collectionRate = $totalTransactions > 0 ? (($paidCount ?? 0) / $totalTransactions) * 100 : 0;
                                        @endphp
                                        <p class="mb-0 text-white opacity-75" style="font-size: 0.55rem;">Rate</p>
                                        <h6 class="mb-0 text-white" style="font-size: 0.9rem; font-weight: 600;">{{ number_format($collectionRate, 1) }}%</h6>
                                        <span class="text-white-50" style="font-size: 0.45rem;">Filtered</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filter Section - Compact on mobile -->
                    <div class="mb-3 mb-sm-4" style="background: #f8fafd; border-radius: 16px; border: 1px solid #eef2f8; overflow: hidden;">
                        <div class="p-2 p-sm-3" style="background: white; border-bottom: 1px solid #eef2f8;">
                            <h6 class="mb-0">
                                <a class="text-decoration-none d-flex align-items-center" data-bs-toggle="collapse" href="#filterSection" role="button" style="color: #1e293b;">
                                    <i class="fas fa-filter me-2" style="color: #2563eb; font-size: 0.8rem;"></i>
                                    <span style="font-weight: 600; font-size: 0.85rem;">Filter</span>
                                    <i class="fas fa-chevron-down ms-auto" style="font-size: 0.7rem; color: #94a3b8;"></i>
                                </a>
                            </h6>
                        </div>
                        <div class="collapse" id="filterSection">
                            <div class="p-2 p-sm-4">
                                <form action="{{ route('transactions.index') }}" method="GET">
                                    <div class="row g-1 g-sm-3">
                                        <div class="col-12 col-md-3">
                                            <select name="donor_id" class="form-select form-select-sm" style="border-radius: 20px; padding: 6px 10px; border: 1px solid #e2e8f0; font-size: 0.8rem;">
                                                <option value="">All Donors</option>
                                                @foreach($donors ?? [] as $donor)
                                                    <option value="{{ $donor->id }}" {{ request('donor_id') == $donor->id ? 'selected' : '' }}>
                                                        {{ $donor->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-12 col-md-2">
                                            <select name="month_id" class="form-select form-select-sm" style="border-radius: 20px; padding: 6px 10px; border: 1px solid #e2e8f0; font-size: 0.8rem;">
                                                <option value="">All Months</option>
                                                @foreach($months ?? [] as $month)
                                                    <option value="{{ $month->id }}" {{ request('month_id') == $month->id ? 'selected' : '' }}>
                                                        {{ $month->name }} {{ $month->year }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-12 col-md-2">
                                            <select name="status" class="form-select form-select-sm" style="border-radius: 20px; padding: 6px 10px; border: 1px solid #e2e8f0; font-size: 0.8rem;">
                                                <option value="">All Status</option>
                                                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                                <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                                            </select>
                                        </div>
                                        <div class="col-12 col-md-2">
                                            <select name="payment_method" class="form-select form-select-sm" style="border-radius: 20px; padding: 6px 10px; border: 1px solid #e2e8f0; font-size: 0.8rem;">
                                                <option value="">All Methods</option>
                                                <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                                <option value="bkash" {{ request('payment_method') == 'bkash' ? 'selected' : '' }}>bKash</option>
                                                <option value="nagad" {{ request('payment_method') == 'nagad' ? 'selected' : '' }}>Nagad</option>
                                            </select>
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <div class="d-flex flex-column flex-sm-row gap-1">
                                                <input type="date" name="from_date" class="form-control form-control-sm" value="{{ request('from_date') }}" placeholder="From" style="border-radius: 20px; padding: 6px 10px; border: 1px solid #e2e8f0; font-size: 0.8rem;">
                                                <input type="date" name="to_date" class="form-control form-control-sm" value="{{ request('to_date') }}" placeholder="To" style="border-radius: 20px; padding: 6px 10px; border: 1px solid #e2e8f0; font-size: 0.8rem;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column flex-sm-row justify-content-end gap-1 mt-2 mt-sm-3">
                                        <button type="submit" class="btn w-100 w-sm-auto" style="background: linear-gradient(145deg, #2563eb, #1d4ed8); color: white; border: none; border-radius: 20px; padding: 6px 16px; font-weight: 500; font-size: 0.8rem;">
                                            <i class="fas fa-search me-1"></i>Apply
                                        </button>
                                        <a href="{{ route('transactions.index') }}" class="btn w-100 w-sm-auto" style="background: #f1f5f9; color: #475569; border: none; border-radius: 20px; padding: 6px 16px; font-weight: 500; font-size: 0.8rem;">
                                            <i class="fas fa-redo me-1"></i>Reset
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile transaction cards - Compact padding (Visible only on mobile) -->
                    <div class="d-block d-md-none">
                        @forelse($transactions as $transaction)
                        <div class="mobile-transaction-card mb-2 p-2" style="background: white; border-radius: 16px; box-shadow: 0 2px 8px -2px rgba(0,0,0,0.03); border: 1px solid #edf2f7;">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <div>
                                    <span class="fw-bold" style="color: #2563eb; font-size: 0.8rem;">#{{ $transaction->id }}</span>
                                    <div class="mt-0">
                                        <a href="{{ route('donors.show', $transaction->donor) }}" class="text-decoration-none fw-medium" style="color: #1e293b; font-size: 0.9rem;">
                                            {{ $transaction->donor->name }}
                                        </a>
                                    </div>
                                </div>
                                <span class="badge" style="background: {{ $transaction->paid_status == 'paid' ? '#dcfce7' : '#fee2e2' }}; color: {{ $transaction->paid_status == 'paid' ? '#166534' : '#991b1b' }}; padding: 4px 8px; border-radius: 20px; font-size: 0.6rem;">
                                    <i class="fas fa-{{ $transaction->paid_status == 'paid' ? 'check-circle' : 'clock' }} me-1"></i>
                                    {{ substr($transaction->paid_status, 0, 6) }}
                                </span>
                            </div>
                            
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
                                    <span class="badge" style="background: #eef2ff; color: #4338ca; padding: 3px 6px; border-radius: 16px; font-size: 0.6rem;">
                                        @if($transaction->payment_method == 'bkash')
                                            <i class="fas fa-mobile-alt me-1"></i>
                                        @elseif($transaction->payment_method == 'nagad')
                                            <i class="fas fa-mobile me-1"></i>
                                        @else
                                            <i class="fas fa-money-bill me-1"></i>
                                        @endif
                                        {{ ucfirst(substr($transaction->payment_method, 0, 5)) }}
                                    </span>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block" style="font-size: 0.6rem;">Date</small>
                                    <span style="font-size: 0.7rem;">{{ $transaction->created_at->format('d M') }}</span>
                                </div>
                            </div>
                            
                            <div class="d-flex gap-1 mt-1 pt-1" style="border-top: 1px solid #edf2f7;">
                                <a href="{{ route('transactions.show', $transaction) }}" class="btn btn-sm flex-fill" style="background: #f8fafc; color: #475569; border-radius: 16px; padding: 6px 0; border: 1px solid #e2e8f0; font-size: 0.7rem;">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <a href="{{ route('transactions.edit', $transaction) }}" class="btn btn-sm flex-fill" style="background: #f8fafc; color: #2563eb; border-radius: 16px; padding: 6px 0; border: 1px solid #e2e8f0; font-size: 0.7rem;">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                @if($transaction->paid_status == 'unpaid')
                                <form action="{{ route('transactions.markAsPaid', $transaction) }}" method="POST" class="flex-fill">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm w-100" style="background: #f8fafc; color: #10b981; border-radius: 16px; padding: 6px 0; border: 1px solid #e2e8f0; font-size: 0.7rem;" onclick="return confirm('Mark as paid?')">
                                        <i class="fas fa-check"></i> Pay
                                    </button>
                                </form>
                                @endif
                                <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" class="flex-fill">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm w-100" style="background: #f8fafc; color: #ef4444; border-radius: 16px; padding: 6px 0; border: 1px solid #e2e8f0; font-size: 0.7rem;" onclick="return confirm('Delete?')">
                                        <i class="fas fa-trash"></i> Del
                                    </button>
                                </form>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-4">
                            <div style="width: 48px; height: 48px; background: #f1f5f9; border-radius: 16px; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px;">
                                <i class="fas fa-exchange-alt" style="color: #94a3b8; font-size: 1.2rem;"></i>
                            </div>
                            <h6 style="color: #475569; font-size: 0.9rem; margin-bottom: 8px;">No transactions</h6>
                            <a href="{{ route('transactions.create') }}" class="btn" style="background: linear-gradient(145deg, #2563eb, #1d4ed8); color: white; border: none; border-radius: 20px; padding: 6px 16px; font-size: 0.8rem;">
                                <i class="fas fa-plus me-1"></i>Add First
                            </a>
                        </div>
                        @endforelse
                        
                        <!-- Mobile Pagination -->
                        <div class="d-flex justify-content-center mt-3">
                            {{ $transactions->withQueryString()->links() }}
                        </div>
                    </div>

                    <!-- Desktop Table View (Visible only on desktop) -->
                    <div class="d-none d-md-block">
    <div class="table-responsive">
        <table class="table align-middle" style="border-collapse: separate; border-spacing: 0 4px; width: 100%;">
            <thead>
                <tr style="background: transparent;">
                    <th style="border: none; padding: 8px 10px; color: #64748b; font-weight: 600; font-size: 0.7rem; text-transform: uppercase;">ID</th>
                    <th style="border: none; padding: 8px 10px; color: #64748b; font-weight: 600; font-size: 0.7rem; text-transform: uppercase;">Donor</th>
                    <th style="border: none; padding: 8px 10px; color: #64748b; font-weight: 600; font-size: 0.7rem; text-transform: uppercase;">Month</th>
                    <th style="border: none; padding: 8px 10px; color: #64748b; font-weight: 600; font-size: 0.7rem; text-transform: uppercase;">Amount</th>
                    <th style="border: none; padding: 8px 10px; color: #64748b; font-weight: 600; font-size: 0.7rem; text-transform: uppercase;">Method</th>
                    <th style="border: none; padding: 8px 10px; color: #64748b; font-weight: 600; font-size: 0.7rem; text-transform: uppercase;">Status</th>
                    <th style="border: none; padding: 8px 10px; color: #64748b; font-weight: 600; font-size: 0.7rem; text-transform: uppercase;">By</th>
                    <th style="border: none; padding: 8px 10px; color: #64748b; font-weight: 600; font-size: 0.7rem; text-transform: uppercase;">Date</th>
                    <th style="border: none; padding: 8px 10px; color: #64748b; font-weight: 600; font-size: 0.7rem; text-transform: uppercase;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $transaction)
                <tr style="background: white; border-radius: 12px; box-shadow: 0 2px 6px -2px rgba(0,0,0,0.02); transition: all 0.2s;">
                    <td style="padding: 8px 10px; border: none; border-radius: 12px 0 0 12px; color: #64748b; font-weight: 500; font-size: 0.8rem;">#{{ $transaction->id }}</td>
                    <td style="padding: 8px 10px; border: none;">
                        <a href="{{ route('donors.show', $transaction->donor) }}" class="text-decoration-none" style="color: #1e293b; font-size: 0.85rem; font-weight: 500;">
                            {{ $transaction->donor->name }}
                        </a>
                    </td>
                    <td style="padding: 8px 10px; border: none; color: #475569; font-size: 0.8rem;">{{ $transaction->month->name }}'{{ substr($transaction->month->year, 2) }}</td>
                    <td style="padding: 8px 10px; border: none; font-weight: 600; color: #2563eb; font-size: 0.85rem;">৳{{ number_format($transaction->amount) }}</td>
                    <td style="padding: 8px 10px; border: none;">
                        <span style="background: #eef2ff; color: #4338ca; padding: 3px 8px; border-radius: 16px; font-size: 0.65rem; font-weight: 500; display: inline-flex; align-items: center;">
                            @if($transaction->payment_method == 'bkash')
                                <i class="fas fa-mobile-alt me-1" style="font-size: 0.6rem;"></i>
                            @elseif($transaction->payment_method == 'nagad')
                                <i class="fas fa-mobile me-1" style="font-size: 0.6rem;"></i>
                            @else
                                <i class="fas fa-money-bill me-1" style="font-size: 0.6rem;"></i>
                            @endif
                            {{ substr($transaction->payment_method, 0, 5) }}
                        </span>
                    </td>
                    <td style="padding: 8px 10px; border: none;">
                        @if($transaction->paid_status == 'paid')
                            <span style="background: #dcfce7; color: #166534; padding: 3px 8px; border-radius: 16px; font-size: 0.65rem; font-weight: 500;">
                                <i class="fas fa-check-circle me-1" style="font-size: 0.6rem;"></i>Paid
                            </span>
                        @else
                            <span style="background: #fee2e2; color: #991b1b; padding: 3px 8px; border-radius: 16px; font-size: 0.65rem; font-weight: 500;">
                                <i class="fas fa-times-circle me-1" style="font-size: 0.6rem;"></i>Due
                            </span>
                        @endif
                    </td>
                    <td style="padding: 8px 10px; border: none; color: #64748b; font-size: 0.75rem;">{{ substr($transaction->user->name, 0, 8) }}{{ strlen($transaction->user->name) > 8 ? '...' : '' }}</td>
                    <td style="padding: 8px 10px; border: none; color: #64748b; font-size: 0.75rem;">{{ $transaction->created_at->format('d M') }}</td>
                    <td style="padding: 8px 10px; border: none; border-radius: 0 12px 12px 0;">
                        <div class="d-flex gap-1">
                            <a href="{{ route('transactions.show', $transaction) }}" 
                               class="btn btn-sm" 
                               style="width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; color: #64748b; padding: 0; font-size: 0.7rem;">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('transactions.edit', $transaction) }}" 
                               class="btn btn-sm" 
                               style="width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; color: #64748b; padding: 0; font-size: 0.7rem;">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if($transaction->paid_status == 'unpaid')
                            <form action="{{ route('transactions.markAsPaid', $transaction) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" 
                                        class="btn btn-sm" 
                                        style="width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; color: #64748b; padding: 0; font-size: 0.7rem;"
                                        onclick="return confirm('Mark as paid?')">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                            @endif
                            <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="btn btn-sm" 
                                        style="width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; color: #64748b; padding: 0; font-size: 0.7rem;"
                                        onclick="return confirm('Delete?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="padding: 32px; text-align: center; background: white; border-radius: 12px;">
                        <div style="width: 48px; height: 48px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px;">
                            <i class="fas fa-exchange-alt" style="font-size: 18px; color: #94a3b8;"></i>
                        </div>
                        <h6 style="color: #475569; margin-bottom: 4px; font-size: 0.9rem;">No Transactions</h6>
                        <p style="color: #64748b; font-size: 0.75rem; margin-bottom: 12px;">Add your first transaction</p>
                        <a href="{{ route('transactions.create') }}" class="btn" style="background: linear-gradient(145deg, #2563eb, #1d4ed8); color: white; border: none; border-radius: 20px; padding: 6px 16px; font-weight: 500; font-size: 0.8rem;">
                            <i class="fas fa-plus me-1"></i>Add
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Desktop Pagination -->
    <div class="d-flex justify-content-center mt-3">
        {{ $transactions->withQueryString()->links() }}
    </div>
</div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Mobile-first optimizations - reduced padding/margins */
@media (max-width: 768px) {
    .container-fluid {
        padding-left: 0 !important;
        padding-right: 0 !important;
    }
    
    .content-card {
        border-radius: 0 !important;
    }
    
    .stat-compact {
        border-radius: 10px !important;
        padding: 8px !important;
    }
    
    .stat-compact .d-flex {
        gap: 6px !important;
    }
    
    .mobile-transaction-card {
        margin-bottom: 6px !important;
    }
    
    /* Tighter spacing for mobile */
    .row.g-1 {
        margin: -2px !important;
    }
    
    .row.g-1 > [class*="col-"] {
        padding: 2px !important;
    }
    
    /* Smaller touch targets but still tappable */
    .btn-sm {
        min-height: 36px;
        font-size: 0.7rem;
    }
    
    /* Even more compact for action icons */
    .mobile-transaction-card .btn {
        padding: 4px 0 !important;
    }
}

/* Desktop hover effects */
@media (min-width: 769px) {
    tbody tr {
        transition: all 0.2s ease;
    }
    
    tbody tr:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 24px -8px rgba(37, 99, 235, 0.15) !important;
    }
    
    .stat-compact:hover {
        transform: translateY(-2px);
        transition: transform 0.2s;
    }
}

/* Tablet adjustments */
@media (min-width: 769px) and (max-width: 1024px) {
    .stat-compact {
        padding: 12px !important;
    }
    
    .stat-compact h6 {
        font-size: 1rem !important;
    }
}
</style>
@endsection