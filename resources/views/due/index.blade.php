@extends('layouts.app')

@section('title', 'Due Payments')
@section('page-title', 'Due Payments')
@section('page-subtitle', 'Track pending payments from all donors')

@section('quick-actions')
    {{-- <a href="{{ route('due.export') }}" class="quick-action">
        <i class="fas fa-file-export"></i> Export Report
    </a> --}}
    <a href="#" class="quick-action" onclick="window.print()">
        <i class="fas fa-print"></i> Print
    </a>
    <button type="button" class="quick-action" data-bs-toggle="modal" data-bs-target="#bulkSmsModal">
        <i class="fas fa-bell"></i> Remind All
    </button>
@endsection

@section('content')
<div class="container-fluid px-2 px-sm-3">
    <!-- Summary Cards -->
    <div class="row g-2 g-sm-3 mb-3">
        <div class="col-6 col-md-3">
            <div class="stat-compact p-2 p-sm-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px;">
                <div class="d-flex align-items-center gap-1 gap-sm-3">
                    <div style="width: 32px; height: 32px; background: rgba(255,255,255,0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-users" style="font-size: 0.9rem; color: white;"></i>
                    </div>
                    <div>
                        <p class="mb-0 text-white opacity-75" style="font-size: 0.55rem;">TOTAL DONORS</p>
                        <h6 class="mb-0 text-white" style="font-size: 0.9rem; font-weight: 600;">{{ $summary['total_donors'] }}</h6>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-6 col-md-3">
            <div class="stat-compact p-2 p-sm-3" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 12px;">
                <div class="d-flex align-items-center gap-1 gap-sm-3">
                    <div style="width: 32px; height: 32px; background: rgba(255,255,255,0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-exclamation-triangle" style="font-size: 0.9rem; color: white;"></i>
                    </div>
                    <div>
                        <p class="mb-0 text-white opacity-75" style="font-size: 0.55rem;">WITH DUE</p>
                        <h6 class="mb-0 text-white" style="font-size: 0.9rem; font-weight: 600;">{{ $summary['donors_with_due'] }}</h6>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-6 col-md-3">
            <div class="stat-compact p-2 p-sm-3" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); border-radius: 12px;">
                <div class="d-flex align-items-center gap-1 gap-sm-3">
                    <div style="width: 32px; height: 32px; background: rgba(255,255,255,0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-coins" style="font-size: 0.9rem; color: white;"></i>
                    </div>
                    <div>
                        <p class="mb-0 text-white opacity-75" style="font-size: 0.55rem;">TOTAL DUE</p>
                        <h6 class="mb-0 text-white" style="font-size: 0.9rem; font-weight: 600;">৳{{ number_format($summary['total_due_amount']) }}</h6>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-6 col-md-3">
            <div class="stat-compact p-2 p-sm-3" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border-radius: 12px;">
                <div class="d-flex align-items-center gap-1 gap-sm-3">
                    <div style="width: 32px; height: 32px; background: rgba(255,255,255,0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-chart-line" style="font-size: 0.9rem; color: white;"></i>
                    </div>
                    <div>
                        <p class="mb-0 text-white opacity-75" style="font-size: 0.55rem;">AVG PER DONOR</p>
                        <h6 class="mb-0 text-white" style="font-size: 0.9rem; font-weight: 600;">৳{{ number_format($summary['average_due_per_donor']) }}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Due List Card -->
    <div class="content-card" style="border-radius: 24px; overflow: hidden; background: white; box-shadow: 0 20px 40px -12px rgba(0,20,40,0.12); border: 1px solid rgba(226, 232, 240, 0.6);">
        <div class="px-4 py-3" style="background: linear-gradient(145deg, #fef2f2, #fee2e2); border-bottom: 1px solid #fecaca;">
            <div class="d-flex align-items-center justify-content-between">
                <h5 class="mb-0" style="font-weight: 600; color: #991b1b; font-size: 1rem;">
                    <i class="fas fa-clock me-2" style="color: #dc2626;"></i>
                    Donors with Pending Payments
                </h5>
                <span class="badge rounded-pill px-3 py-1" style="background: #dc2626; color: white; font-size: 0.7rem;">
                    {{ $summary['donors_with_due'] }} Donors
                </span>
            </div>
        </div>

        <div class="p-3">
            @if (count($dueData) > 0)
                <!-- Desktop Table View -->
                <div class="d-none d-md-block">
                    <div class="table-responsive">
                        <table class="table" style="border-collapse: separate; border-spacing: 0 8px; width: 100%; table-layout: fixed;">
                            <colgroup>
                                <col style="width: 20%;">
                                <col style="width: 15%;">
                                <col style="width: 20%;">
                                <col style="width: 15%;">
                                <col style="width: 15%;">
                                <col style="width: 15%;">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th style="padding: 8px 12px; font-size: 0.7rem; font-weight: 600; color: #64748b;">Donor</th>
                                    <th style="padding: 8px 12px; font-size: 0.7rem; font-weight: 600; color: #64748b;">Contact</th>
                                    <th style="padding: 8px 12px; font-size: 0.7rem; font-weight: 600; color: #64748b;">Due Months</th>
                                    <th style="padding: 8px 12px; font-size: 0.7rem; font-weight: 600; color: #64748b;">Total Due</th>
                                    {{-- <th style="padding: 8px 12px; font-size: 0.7rem; font-weight: 600; color: #64748b;">Status</th> --}}
                                    <th style="padding: 8px 12px; font-size: 0.7rem; font-weight: 600; color: #64748b;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dueData as $item)
                                <tr style="background: white; border-radius: 16px; box-shadow: 0 2px 8px -2px rgba(0,0,0,0.02); border: 1px solid #edf2f7; height: 80px;"
                                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 16px -4px rgba(220,38,38,0.1)'"
                                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px -2px rgba(0,0,0,0.02)'">
                                    <td style="padding: 12px; border: none; border-radius: 16px 0 0 16px;">
                                        <div class="d-flex align-items-center gap-2">
                                            <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                                <span class="text-white fw-bold">{{ substr($item['donor']->name, 0, 1) }}</span>
                                            </div>
                                            <div>
                                                <span style="font-weight: 600; font-size: 0.9rem; color: #1e293b;">{{ $item['donor']->name }}</span>
                                                <span style="font-size: 0.7rem; color: #64748b; display: block;">ID: #{{ $item['donor']->id }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="padding: 12px; border: none;">
                                        <div>
                                            @if ($item['donor']->phone)
                                                <a href="tel:{{ $item['donor']->phone }}"
                                                    style="font-size: 0.8rem; color: #2563eb; display: block; text-decoration: none; transition: all 0.2s;"
                                                    onmouseover="this.style.color='#1e40af'; this.style.textDecoration='underline'"
                                                    onmouseout="this.style.color='#2563eb'; this.style.textDecoration='none'"
                                                    title="Call {{ $item['donor']->name }}">
                                                    <i class="fas fa-phone-alt me-1" style="font-size: 0.6rem;"></i>
                                                    {{ $item['donor']->phone }}
                                                </a>
                                            @else
                                                <span style="font-size: 0.8rem; color: #94a3b8; display: block;">
                                                    <i class="fas fa-phone-alt me-1" style="font-size: 0.6rem;"></i>
                                                    No phone
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td style="padding: 12px; border: none;">
                                        <div>
                                            <span style="font-weight: 600; font-size: 0.9rem; color: #dc2626;">{{ $item['due_count'] }}</span>
                                            <span style="font-size: 0.7rem; color: #64748b; margin-left: 4px;">months</span>
                                        </div>
                                        @if ($item['oldest_due'])
                                            <span style="font-size: 0.65rem; color: #64748b; display: block;">
                                                Oldest: {{ $item['oldest_due']->format('M Y') }}
                                            </span>
                                        @endif
                                    </td>
                                    <td style="padding: 12px; border: none;">
                                        <span style="font-weight: 700; font-size: 1rem; color: #dc2626;">৳{{ number_format($item['total_due']) }}</span>
                                    </td>
                                    {{-- <td style="padding: 12px; border: none;">
                                        @if ($item['max_days_overdue'] > 30)
                                            <span class="badge rounded-pill px-2 py-1" style="background: #fee2e2; color: #991b1b; font-size: 0.65rem;">
                                                <i class="fas fa-exclamation-circle me-1"></i> Urgent
                                            </span>
                                        @elseif($item['max_days_overdue'] > 0)
                                            <span class="badge rounded-pill px-2 py-1" style="background: #fef3c7; color: #92400e; font-size: 0.65rem;">
                                                Overdue
                                            </span>
                                        @else
                                            <span class="badge rounded-pill px-2 py-1" style="background: #f1f5f9; color: #475569; font-size: 0.65rem;">
                                                Pending
                                            </span>
                                        @endif
                                    </td> --}}
                                    <td style="padding: 12px; border: none; border-radius: 0 16px 16px 0;">
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('donors.show', $item['donor']) }}" class="btn btn-sm"
                                                style="width: 32px; height: 32px; background: #f1f5f9; border-radius: 8px; color: #475569; display: flex; align-items: center; justify-content: center;"
                                                title="View Donor">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if ($item['donor']->phone)
                                                <a href="tel:{{ $item['donor']->phone }}" class="btn btn-sm"
                                                    style="width: 32px; height: 32px; background: #10b981; color: white; border-radius: 8px; display: flex; align-items: center; justify-content: center;"
                                                    title="Call Donor">
                                                    <i class="fas fa-phone"></i>
                                                </a>
                                            @endif
                                            <a href="{{ route('transactions.create', ['donor_id' => $item['donor']->id]) }}"
                                                class="btn btn-sm"
                                                style="width: 32px; height: 32px; background: #2563eb; color: white; border-radius: 8px; display: flex; align-items: center; justify-content: center;"
                                                title="Add Payment">
                                                <i class="fas fa-plus"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm send-sms-btn"
                                                style="width: 32px; height: 32px; background: #f59e0b; color: white; border-radius: 8px; display: flex; align-items: center; justify-content: center; border: none; cursor: pointer;"
                                                title="Send Reminder SMS" 
                                                data-donor-id="{{ $item['donor']->id }}"
                                                data-donor-name="{{ $item['donor']->name }}"
                                                data-donor-phone="{{ $item['donor']->phone }}"
                                                data-due-amount="{{ $item['total_due'] }}"
                                                data-due-months="{{ $item['due_count'] }}">
                                                <i class="fas fa-bell"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Mobile Card View -->
                <div class="d-block d-md-none">
                    @foreach ($dueData as $item)
                    <div class="mobile-donor-card mb-2 p-2" style="background: white; border-radius: 16px; box-shadow: 0 2px 8px -2px rgba(0,0,0,0.03); border: 1px solid #fee2e2;">
                        <!-- Donor Header -->
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="d-flex align-items-center gap-2">
                                <div style="width: 44px; height: 44px; background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                    <span class="text-white fw-bold fs-5">{{ substr($item['donor']->name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <h6 class="mb-0" style="font-weight: 600;">{{ $item['donor']->name }}</h6>
                                    @if ($item['donor']->phone)
                                        <a href="tel:{{ $item['donor']->phone }}"
                                            style="font-size: 0.7rem; color: #2563eb; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                            <i class="fas fa-phone-alt me-1"></i>{{ $item['donor']->phone }}
                                        </a>
                                    @else
                                        <span style="font-size: 0.7rem; color: #94a3b8;">
                                            <i class="fas fa-phone-alt me-1"></i>No phone
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <span style="font-weight: 700; font-size: 1.1rem; color: #dc2626;">৳{{ number_format($item['total_due']) }}</span>
                        </div>

                        <!-- Due Summary -->
                        <div class="row g-1 mb-2">
                            <div class="col-4">
                                <div style="background: #fef2f2; border-radius: 8px; padding: 6px; text-align: center;">
                                    <small style="font-size: 0.6rem; color: #64748b;">Months</small>
                                    <div style="font-weight: 600; font-size: 0.9rem; color: #dc2626;">{{ $item['due_count'] }}</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div style="background: #fef2f2; border-radius: 8px; padding: 6px; text-align: center;">
                                    <small style="font-size: 0.6rem; color: #64748b;">Oldest</small>
                                    <div style="font-weight: 500; font-size: 0.75rem;">
                                        {{ $item['oldest_due'] ? $item['oldest_due']->format('M Y') : 'N/A' }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div style="background: #fef2f2; border-radius: 8px; padding: 6px; text-align: center;">
                                    <small style="font-size: 0.6rem; color: #64748b;">Status</small>
                                    @if ($item['max_days_overdue'] > 30)
                                        <span class="badge rounded-pill px-2 py-0" style="background: #dc2626; color: white; font-size: 0.6rem;">Urgent</span>
                                    @elseif($item['max_days_overdue'] > 0)
                                        <span class="badge rounded-pill px-2 py-0" style="background: #fef3c7; color: #92400e; font-size: 0.6rem;">Overdue</span>
                                    @else
                                        <span style="font-weight: 500; font-size: 0.7rem;">Pending</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-1 mt-2 pt-1" style="border-top: 1px dashed #e2e8f0;">
                            <a href="{{ route('donors.show', $item['donor']) }}" class="btn btn-sm flex-fill"
                                style="background: #f1f5f9; color: #475569; border-radius: 20px; padding: 6px 0; font-size: 0.7rem;">
                                <i class="fas fa-eye me-1"></i> View
                            </a>
                            @if ($item['donor']->phone)
                                <a href="tel:{{ $item['donor']->phone }}" class="btn btn-sm flex-fill" 
                                    style="background: #10b981; color: white; border-radius: 20px; padding: 6px 0; font-size: 0.7rem; text-decoration: none;">
                                    <i class="fas fa-phone me-1"></i> Call
                                </a>
                            @endif
                            <a href="{{ route('transactions.create', ['donor_id' => $item['donor']->id]) }}"
                                class="btn btn-sm flex-fill"
                                style="background: #2563eb; color: white; border-radius: 20px; padding: 6px 0; font-size: 0.7rem;">
                                <i class="fas fa-plus-circle me-1"></i> Pay
                            </a>
                            <button type="button" class="btn btn-sm flex-fill send-sms-btn-mobile"
                                style="background: #f59e0b; color: white; border-radius: 20px; padding: 6px 0; font-size: 0.7rem; border: none;"
                                data-donor-id="{{ $item['donor']->id }}"
                                data-donor-name="{{ $item['donor']->name }}"
                                data-donor-phone="{{ $item['donor']->phone }}"
                                data-due-amount="{{ $item['total_due'] }}"
                                data-due-months="{{ $item['due_count'] }}">
                                <i class="fas fa-bell me-1"></i> Remind
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Individual SMS Modal -->
                <div class="modal fade" id="smsModal" tabindex="-1" aria-labelledby="smsModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content" style="border-radius: 24px; overflow: hidden;">
                            <div class="modal-header" style="background: linear-gradient(145deg, #f8fafc, #f1f5f9); border-bottom: 1px solid #e2e8f0;">
                                <h5 class="modal-title" id="smsModalLabel" style="font-weight: 600;">
                                    <i class="fas fa-bell me-2" style="color: #f59e0b;"></i>
                                    Send Due Reminder
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-4" id="smsModalContent">
                                <!-- Content will be filled by JavaScript -->
                            </div>
                            <div class="modal-footer" style="border-top: 1px solid #e2e8f0;">
                                <button type="button" class="btn" style="background: #f1f5f9; color: #475569; border-radius: 40px; padding: 8px 20px;" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" id="confirmSendSms" class="btn" style="background: linear-gradient(145deg, #f59e0b, #d97706); color: white; border-radius: 40px; padding: 8px 24px;">
                                    <i class="fas fa-paper-plane me-2"></i>Send Reminder
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bulk SMS Modal -->
                <div class="modal fade" id="bulkSmsModal" tabindex="-1" aria-labelledby="bulkSmsModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content" style="border-radius: 24px; overflow: hidden;">
                            <!-- Modal Header -->
                            <div class="modal-header" style="background: linear-gradient(145deg, #f8fafc, #f1f5f9); border-bottom: 1px solid #e2e8f0;">
                                <h5 class="modal-title" id="bulkSmsModalLabel" style="font-weight: 600;">
                                    <i class="fas fa-bell me-2" style="color: #f59e0b;"></i>
                                    Send Bulk Reminders
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            
                            <!-- Modal Body -->
                            <div class="modal-body p-4">
                                <div id="bulkSmsModalContent">
                                    <!-- Summary Cards -->
                                    <div class="row g-2 mb-3">
                                        <div class="col-6">
                                            <div class="p-2 text-center rounded-3" style="background: linear-gradient(145deg, #fef2f2, #fee2e2);">
                                                <small class="text-danger d-block" style="font-size: 0.65rem;">DONORS</small>
                                                <span class="fw-bold" style="font-size: 1.2rem; color: #dc2626;">{{ $summary['donors_with_due'] }}</span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="p-2 text-center rounded-3" style="background: linear-gradient(145deg, #fef2f2, #fee2e2);">
                                                <small class="text-danger d-block" style="font-size: 0.65rem;">TOTAL DUE</small>
                                                <span class="fw-bold" style="font-size: 1.2rem; color: #dc2626;">৳{{ number_format($summary['total_due_amount']) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Message Preview -->
                                    <div class="mb-3">
                                        <label class="form-label" style="font-weight: 500; font-size: 0.85rem;">Message Preview (Bangla)</label>
                                        <div class="p-3 rounded-3" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                                            <p class="mb-0" style="font-size: 0.85rem;">
                                                জনাব [নাম], মসজিদ ফান্ডে আপনার [মাস] মাসের বকেয়া ৳[টাকা] টাকা রয়েছে। অনুগ্রহ করে দ্রুত পরিশোধ করুন। জাযাকাল্লাহু খাইরান।
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label" style="font-weight: 500; font-size: 0.85rem;">Message Preview (English)</label>
                                        <div class="p-3 rounded-3" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                                            <p class="mb-0" style="font-size: 0.85rem;">
                                                Dear [Name], you have [months] month(s) pending payment of ৳[amount] at MosqueFund. Please clear your dues. Jazakallah Khair.
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <!-- Stats Summary -->
                                    <div class="alert alert-warning" style="background: #fef3c7; border-color: #fde68a; color: #92400e;">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <strong>{{ $summary['donors_with_due'] }} donors</strong> will receive this SMS reminder.
                                        <span class="d-block mt-1 small">Total cost: ৳{{ number_format($summary['donors_with_due'] * 0.35, 2) }} (approx.)</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Modal Footer -->
                            <div class="modal-footer" style="border-top: 1px solid #e2e8f0;">
                                <button type="button" class="btn" style="background: #f1f5f9; color: #475569; border-radius: 40px; padding: 8px 20px;" data-bs-dismiss="modal">
                                    Cancel
                                </button>
                                <button type="button" id="confirmBulkSms" class="btn" style="background: linear-gradient(145deg, #f59e0b, #d97706); color: white; border-radius: 40px; padding: 8px 24px;">
                                    <i class="fas fa-paper-plane me-2"></i>Send to All
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- No Due Donors -->
                <div class="text-center py-5">
                    <div style="width: 80px; height: 80px; background: #ecfdf5; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                        <i class="fas fa-check-circle fa-3x" style="color: #10b981;"></i>
                    </div>
                    <h5 style="color: #1e293b; margin-bottom: 8px;">No Due Payments</h5>
                    <p style="color: #64748b; margin-bottom: 20px;">All donors are up to date with their payments</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Styles -->
<style>
    /* Phone link styles */
    a[href^="tel"] {
        transition: all 0.2s ease;
        -webkit-tap-highlight-color: rgba(16, 185, 129, 0.1);
    }

    a[href^="tel"]:hover {
        transform: translateX(2px);
    }

    a[href^="tel"]:active {
        opacity: 0.7;
        transform: scale(0.98);
    }

    /* Mobile optimizations for phone links */
    @media (max-width: 768px) {
        a[href^="tel"] {
            padding: 4px 0;
            display: inline-block;
        }

        /* Better touch target */
        .mobile-donor-card a[href^="tel"] {
            min-height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    }

    /* Toast animation */
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
</style>

@push('scripts')
<script>
    $(document).ready(function() {
        let currentDonorData = null;

        // Handle desktop SMS button click
        $('.send-sms-btn').on('click', function() {
            currentDonorData = {
                id: $(this).data('donor-id'),
                name: $(this).data('donor-name'),
                phone: $(this).data('donor-phone'),
                amount: $(this).data('due-amount'),
                months: $(this).data('due-months')
            };

            showSmsModal(currentDonorData);
        });

        // Handle mobile SMS button click
        $('.send-sms-btn-mobile').on('click', function() {
            currentDonorData = {
                id: $(this).data('donor-id'),
                name: $(this).data('donor-name'),
                phone: $(this).data('donor-phone'),
                amount: $(this).data('due-amount'),
                months: $(this).data('due-months')
            };

            showSmsModal(currentDonorData);
        });

        function showSmsModal(donor) {
            const amountFormatted = parseFloat(donor.amount).toLocaleString();

            // Bangla message
            const banglaMessage = `জনাব ${donor.name}, মসজিদ ফান্ডে আপনার ${donor.months} মাসের বকেয়া ৳${amountFormatted} টাকা রয়েছে। অনুগ্রহ করে দ্রুত পরিশোধ করুন। জাযাকাল্লাহু খাইরান।`;

            // English message
            const englishMessage = `Dear ${donor.name}, you have ${donor.months} month(s) pending payment of ৳${amountFormatted} at MosqueFund. Please clear your dues. Jazakallah Khair.`;

            $('#smsModalContent').html(`
                <div class="mb-3">
                    <div class="d-flex align-items-center gap-3 mb-3 p-3 rounded-3" style="background: #f8fafc;">
                        <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                            <span class="text-white fw-bold fs-5">${donor.name.substring(0,1)}</span>
                        </div>
                        <div>
                            <h6 class="mb-1" style="font-weight: 600;">${donor.name}</h6>
                            <span style="font-size: 0.8rem; color: #2563eb;">
                                <i class="fas fa-phone-alt me-1"></i>${donor.phone}
                            </span>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label" style="font-weight: 500;">Message Preview (Bangla)</label>
                        <div class="p-3 rounded-3" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                            <p class="mb-0" style="font-size: 0.9rem;">${banglaMessage}</p>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label" style="font-weight: 500;">Message Preview (English)</label>
                        <div class="p-3 rounded-3" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                            <p class="mb-0" style="font-size: 0.9rem;">${englishMessage}</p>
                        </div>
                    </div>
                    
                    <div class="alert alert-info" style="background: #eef2ff; border-color: #c7d2fe; color: #1e40af;">
                        <i class="fas fa-info-circle me-2"></i>
                        This reminder will be sent to <strong>${donor.phone}</strong>
                    </div>
                </div>
            `);

            $('#smsModal').modal('show');
        }

        // Handle confirm send for individual SMS
        $('#confirmSendSms').on('click', function() {
            if (!currentDonorData) return;

            const $btn = $(this);
            const originalText = $btn.html();

            $btn.html('<span class="spinner-border spinner-border-sm me-2"></span>Sending...').prop('disabled', true);

            $.ajax({
                url: '{{ route("due.send-reminder") }}',
                method: 'POST',
                data: {
                    donor_id: currentDonorData.id,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#smsModal').modal('hide');
                    if (response.success) {
                        showToast('success', response.message);
                    } else {
                        showToast('error', response.message);
                    }
                },
                error: function() {
                    $('#smsModal').modal('hide');
                    showToast('error', 'Failed to send SMS. Please try again.');
                },
                complete: function() {
                    $btn.html(originalText).prop('disabled', false);
                }
            });
        });

        // Handle bulk SMS confirmation
        $('#confirmBulkSms').on('click', function() {
            const $btn = $(this);
            const originalText = $btn.html();

            $btn.html('<span class="spinner-border spinner-border-sm me-2"></span>Sending...').prop('disabled', true);

            $.ajax({
                url: '{{ route("due.send-bulk-reminder") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#bulkSmsModal').modal('hide');
                    
                    let message = 'Bulk reminders sent successfully!';
                    if (typeof response === 'object') {
                        message = response.message || message;
                    }
                    
                    showToast('success', message);
                    
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                },
                error: function(xhr) {
                    $('#bulkSmsModal').modal('hide');
                    
                    let errorMessage = 'Failed to send bulk reminders.';
                    try {
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                    } catch (e) {
                        console.log('Could not parse error response');
                    }
                    
                    showToast('error', errorMessage);
                },
                complete: function() {
                    $btn.html(originalText).prop('disabled', false);
                }
            });
        });

        // Toast notification function
        function showToast(type, message) {
            // Create toast container if it doesn't exist
            if (!$('#toastContainer').length) {
                $('body').append('<div id="toastContainer" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>');
            }
            
            const toastId = 'toast-' + Date.now();
            const bgColor = type === 'success' ? '#10b981' : '#ef4444';
            const icon = type === 'success' ? 'check-circle' : 'exclamation-circle';
            
            const toastHtml = `
                <div id="${toastId}" style="background: white; border-left: 4px solid ${bgColor}; border-radius: 8px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); padding: 12px 20px; margin-bottom: 10px; min-width: 300px; animation: slideIn 0.3s ease;">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-${icon}" style="color: ${bgColor};"></i>
                        <span style="color: #1e293b;">${message}</span>
                    </div>
                </div>
            `;
            
            $('#toastContainer').append(toastHtml);
            
            // Auto remove after 3 seconds
            setTimeout(function() {
                $(`#${toastId}`).fadeOut(300, function() {
                    $(this).remove();
                });
            }, 3000);
        }
    });
</script>
@endpush
@endsection