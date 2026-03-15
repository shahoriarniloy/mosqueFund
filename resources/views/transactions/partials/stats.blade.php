<div class="row g-1 g-sm-2 mb-2">
    <div class="col-6 col-md-3">
        <div class="stat-compact p-2" style="background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 10px;">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="mb-0 text-white opacity-75" style="font-size: 0.6rem;">TOTAL</p>
                    <span class="text-white fw-bold" style="font-size: 0.9rem;">৳{{ number_format($totalAmount ?? 0) }}</span>
                </div>
                <div style="width: 32px; height: 32px; background: rgba(255,255,255,0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-coins" style="font-size: 0.8rem; color: white;"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-6 col-md-3">
        <div class="stat-compact p-2" style="background: linear-gradient(135deg, #10b981, #059669); border-radius: 10px;">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="mb-0 text-white opacity-75" style="font-size: 0.6rem;">PAID</p>
                    <span class="text-white fw-bold" style="font-size: 0.9rem;">{{ $paidCount ?? 0 }}</span>
                </div>
                <div style="width: 32px; height: 32px; background: rgba(255,255,255,0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-check-circle" style="font-size: 0.8rem; color: white;"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-6 col-md-3">
        <div class="stat-compact p-2" style="background: linear-gradient(135deg, #ef4444, #dc2626); border-radius: 10px;">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="mb-0 text-white opacity-75" style="font-size: 0.6rem;">UNPAID</p>
                    <span class="text-white fw-bold" style="font-size: 0.9rem;">{{ $unpaidCount ?? 0 }}</span>
                </div>
                <div style="width: 32px; height: 32px; background: rgba(255,255,255,0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-clock" style="font-size: 0.8rem; color: white;"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-6 col-md-3">
        <div class="stat-compact p-2" style="background: linear-gradient(135deg, #f59e0b, #d97706); border-radius: 10px;">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="mb-0 text-white opacity-75" style="font-size: 0.6rem;">RATE</p>
                    @php
                        $totalCount = ($paidCount ?? 0) + ($unpaidCount ?? 0);
                        $rate = $totalCount > 0 ? round(($paidCount ?? 0) / $totalCount * 100, 1) : 0;
                    @endphp
                    <span class="text-white fw-bold" style="font-size: 0.9rem;">{{ $rate }}%</span>
                </div>
                <div style="width: 32px; height: 32px; background: rgba(255,255,255,0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-chart-pie" style="font-size: 0.8rem; color: white;"></i>
                </div>
            </div>
        </div>
    </div>
</div>