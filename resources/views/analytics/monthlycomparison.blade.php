@extends('layouts.app')

@section('title', 'Monthly Comparison')
@section('page-title', 'Monthly Comparison')
@section('page-subtitle', 'Year-over-year revenue analysis')

@section('quick-actions')
    <a href="#" class="quick-action" onclick="window.print()">
        <i class="fas fa-print"></i> Print
    </a>
    <a href="#" class="quick-action" id="exportChartBtn">
        <i class="fas fa-download"></i> Export
    </a>
@endsection

@section('content')
<div class="container-fluid px-2 px-sm-3">
    <!-- Year Filter -->
    <div class="row g-1 g-sm-2 mb-2">
        <div class="col-12">
            <div class="compact-card p-2" style="background: white; border-radius: 12px; border: 1px solid #eef2f8;">
                <div class="row g-1 align-items-end">
                    <div class="col-12 col-md-4">
                        <label class="form-label small text-muted mb-0" style="font-size: 0.65rem;">Select Year</label>
                        <select id="yearSelect" class="form-select form-select-sm" style="border-radius: 20px; padding: 6px 12px; font-size: 0.8rem;">
                            @php
                                $currentYear = date('Y');
                                $years = range($currentYear - 3, $currentYear);
                            @endphp
                            @foreach($years as $year)
                                <option value="{{ $year }}" {{ $year == $currentYear ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-4">
                        <button id="updateChartBtn" class="btn btn-sm w-100" style="background: linear-gradient(145deg, #2563eb, #1d4ed8); color: white; border: none; border-radius: 20px; padding: 6px 12px; font-size: 0.8rem;">
                            <i class="fas fa-chart-line me-1"></i> Update
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Card -->
    <div class="row g-1 g-sm-2 mb-2">
        <div class="col-12">
            <div class="compact-card" style="background: white; border-radius: 12px; border: 1px solid #edf2f7; overflow: hidden;">
                <div class="px-2 py-1" style="background: linear-gradient(145deg, #f8fafc, #f1f5f9); border-bottom: 1px solid #e2e8f0;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-1">
                            <i class="fas fa-chart-bar" style="color: #8b5cf6; font-size: 0.7rem;"></i>
                            <span style="font-weight: 600; font-size: 0.65rem; color: #1e293b;">MONTHLY REVENUE</span>
                        </div>
                        <span class="badge" style="background: #e2e8f0; color: #475569; font-size: 0.55rem;" id="selectedYearDisplay">{{ date('Y') }}</span>
                    </div>
                </div>
                <div class="p-2">
                    <!-- Mini Legend -->
                    <div class="d-flex gap-2 mb-2 justify-content-end">
                        <div class="d-flex align-items-center gap-1">
                            <span style="width: 8px; height: 8px; background: #f59e0b; border-radius: 2px;"></span>
                            <span style="font-size: 0.55rem; color: #64748b;">Donations</span>
                        </div>
                        <div class="d-flex align-items-center gap-1">
                            <span style="width: 8px; height: 8px; background: #2563eb; border-radius: 2px;"></span>
                            <span style="font-size: 0.55rem; color: #64748b;">Transactions</span>
                        </div>
                        <div class="d-flex align-items-center gap-1">
                            <span style="width: 8px; height: 8px; background: #10b981; border-radius: 2px;"></span>
                            <span style="font-size: 0.55rem; color: #64748b;">Total</span>
                        </div>
                    </div>
                    <!-- Chart Container -->
                    <div style="position: relative; height: 300px; width: 100%;">
                        <canvas id="monthlyComparisonChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards - Compact -->
    <div class="row g-1 g-sm-2 mb-2">
        <div class="col-6 col-md-3">
            <div class="stat-micro p-1" style="background: linear-gradient(145deg, #f59e0b, #d97706); border-radius: 8px;">
                <div class="d-flex align-items-center justify-content-between px-1">
                    <div>
                        <span class="text-white opacity-75" style="font-size: 0.45rem;">DONATIONS</span>
                        <div class="text-white fw-bold total-donations" style="font-size: 0.8rem;">৳0</div>
                    </div>
                    <div style="width: 24px; height: 24px; background: rgba(255,255,255,0.2); border-radius: 6px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-hand-holding-heart" style="font-size: 0.6rem; color: white;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-micro p-1" style="background: linear-gradient(145deg, #2563eb, #1d4ed8); border-radius: 8px;">
                <div class="d-flex align-items-center justify-content-between px-1">
                    <div>
                        <span class="text-white opacity-75" style="font-size: 0.45rem;">TRANSACTIONS</span>
                        <div class="text-white fw-bold total-transactions" style="font-size: 0.8rem;">৳0</div>
                    </div>
                    <div style="width: 24px; height: 24px; background: rgba(255,255,255,0.2); border-radius: 6px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-exchange-alt" style="font-size: 0.6rem; color: white;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-micro p-1" style="background: linear-gradient(145deg, #10b981, #059669); border-radius: 8px;">
                <div class="d-flex align-items-center justify-content-between px-1">
                    <div>
                        <span class="text-white opacity-75" style="font-size: 0.45rem;">GRAND TOTAL</span>
                        <div class="text-white fw-bold grand-total" style="font-size: 0.8rem;">৳0</div>
                    </div>
                    <div style="width: 24px; height: 24px; background: rgba(255,255,255,0.2); border-radius: 6px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-coins" style="font-size: 0.6rem; color: white;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-micro p-1" style="background: linear-gradient(145deg, #8b5cf6, #7c3aed); border-radius: 8px;">
                <div class="d-flex align-items-center justify-content-between px-1">
                    <div>
                        <span class="text-white opacity-75" style="font-size: 0.45rem;">BEST MONTH</span>
                        <div class="text-white fw-bold best-month-name" style="font-size: 0.7rem;">Jan</div>
                    </div>
                    <div class="text-white best-month-amount" style="font-size: 0.6rem;">৳0</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table - Compact -->
    <div class="row g-1 g-sm-2">
        <div class="col-12">
            <div class="compact-card" style="background: white; border-radius: 12px; border: 1px solid #edf2f7; overflow: hidden;">
                <div class="px-2 py-1" style="background: linear-gradient(145deg, #f8fafc, #f1f5f9); border-bottom: 1px solid #e2e8f0;">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-1">
                            <i class="fas fa-table" style="color: #2563eb; font-size: 0.7rem;"></i>
                            <span style="font-weight: 600; font-size: 0.65rem; color: #1e293b;">MONTHLY BREAKDOWN</span>
                        </div>
                        <span class="badge" style="background: #e2e8f0; color: #475569; font-size: 0.55rem;" id="tableYearLabel">{{ date('Y') }}</span>
                    </div>
                </div>
                <div class="p-1" style="max-height: 300px; overflow-y: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                                <th style="padding: 6px 4px; font-size: 0.55rem; color: #64748b; text-align: left;">Month</th>
                                <th style="padding: 6px 4px; font-size: 0.55rem; color: #64748b; text-align: right;">Donations</th>
                                <th style="padding: 6px 4px; font-size: 0.55rem; color: #64748b; text-align: right;">Transactions</th>
                                <th style="padding: 6px 4px; font-size: 0.55rem; color: #64748b; text-align: right;">Total</th>
                                <th style="padding: 6px 4px; font-size: 0.55rem; color: #64748b; text-align: right;">%</th>
                            </tr>
                        </thead>
                        <tbody id="monthlyDataTable">
                            <tr>
                                <td colspan="5" class="text-center py-2">
                                    <div class="spinner-border spinner-border-sm" style="color: #2563eb; width: 14px; height: 14px;"></div>
                                    <span style="font-size: 0.6rem; margin-left: 4px;">Loading...</span>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot id="tableFoot" style="display: none;">
                            <tr style="background: #f8fafc; border-top: 1px solid #e2e8f0;">
                                <th style="padding: 6px 4px; font-size: 0.6rem;">Total</th>
                                <th style="padding: 6px 4px; font-size: 0.6rem; text-align: right;" id="totalDonationsFoot">৳0</th>
                                <th style="padding: 6px 4px; font-size: 0.6rem; text-align: right;" id="totalTransactionsFoot">৳0</th>
                                <th style="padding: 6px 4px; font-size: 0.6rem; text-align: right;" id="totalGrandFoot">৳0</th>
                                <th style="padding: 6px 4px; font-size: 0.6rem; text-align: right;">100%</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let chart = null;
    const ctx = document.getElementById('monthlyComparisonChart').getContext('2d');
    
    // Load initial data
    loadData({{ date('Y') }});
    
    document.getElementById('updateChartBtn').addEventListener('click', function() {
        const year = document.getElementById('yearSelect').value;
        document.getElementById('selectedYearDisplay').textContent = year;
        document.getElementById('tableYearLabel').textContent = year;
        loadData(year);
    });
    
    function loadData(year) {
        document.getElementById('monthlyDataTable').innerHTML = `
            <tr><td colspan="5" class="text-center py-2"><div class="spinner-border spinner-border-sm" style="color: #2563eb;"></div><span style="font-size:0.6rem; margin-left:4px;">Loading...</span></td></tr>
        `;
        document.getElementById('tableFoot').style.display = 'none';
        
        fetch(`{{ route('analytics.get-monthly-data') }}?year=${year}`)
            .then(response => response.json())
            .then(data => {
                updateChart(data);
                updateStats(data);
                updateTable(data);
            })
            .catch(error => {
                document.getElementById('monthlyDataTable').innerHTML = `<tr><td colspan="5" class="text-center py-2 text-danger" style="font-size:0.6rem;">Error loading data</td></tr>`;
            });
    }
    
    function updateChart(data) {
        const months = data.map(item => item.month.substring(0, 3));
        const donations = data.map(item => item.donations);
        const transactions = data.map(item => item.transactions);
        const totals = data.map(item => item.total);
        
        if (chart) chart.destroy();
        
        chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [
                    { 
                        label: 'Donations', 
                        data: donations, 
                        backgroundColor: '#f59e0b', 
                        borderRadius: 4, 
                        barPercentage: 0.6,
                        categoryPercentage: 0.8
                    },
                    { 
                        label: 'Transactions', 
                        data: transactions, 
                        backgroundColor: '#2563eb', 
                        borderRadius: 4, 
                        barPercentage: 0.6,
                        categoryPercentage: 0.8
                    },
                    { 
                        label: 'Total', 
                        data: totals, 
                        type: 'line', 
                        borderColor: '#10b981', 
                        borderWidth: 2, 
                        pointBackgroundColor: '#10b981', 
                        pointBorderColor: 'white', 
                        pointBorderWidth: 1, 
                        pointRadius: 2, 
                        tension: 0.1,
                        yAxisID: 'y'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { display: false }, 
                    tooltip: { 
                        backgroundColor: 'white', 
                        titleColor: '#1e293b', 
                        bodyColor: '#64748b', 
                        borderColor: '#e2e8f0', 
                        borderWidth: 1,
                        callbacks: {
                            label: function(context) {
                                const value = context.raw;
                                const formatted = value.toLocaleString('en-IN', {
                                    maximumFractionDigits: 0
                                });
                                return context.dataset.label + ': ৳' + formatted;
                            }
                        }
                    } 
                },
                scales: { 
                    y: { 
                        beginAtZero: true, 
                        grid: { color: '#f1f5f9' }, 
                        ticks: { 
                            callback: function(value) {
                                return '৳' + value.toLocaleString('en-IN', {
                                    maximumFractionDigits: 0
                                });
                            }, 
                            font: { size: 8 } 
                        } 
                    }, 
                    x: { 
                        grid: { display: false }, 
                        ticks: { font: { size: 8 } } 
                    } 
                }
            }
        });
    }
    
    function updateStats(data) {
        let totalDonations = 0, totalTransactions = 0, grandTotal = 0, bestMonth = { name: '', amount: 0 };
        data.forEach(item => {
            totalDonations += item.donations;
            totalTransactions += item.transactions;
            grandTotal += item.total;
            if (item.total > bestMonth.amount) bestMonth = { name: item.month.substring(0, 3), amount: item.total };
        });
        
        const formatNumber = (num) => {
            return num.toLocaleString('en-IN', { maximumFractionDigits: 0 });
        };
        
        document.querySelector('.total-donations').textContent = '৳' + formatNumber(totalDonations);
        document.querySelector('.total-transactions').textContent = '৳' + formatNumber(totalTransactions);
        document.querySelector('.grand-total').textContent = '৳' + formatNumber(grandTotal);
        document.querySelector('.best-month-name').textContent = bestMonth.name;
        document.querySelector('.best-month-amount').textContent = '৳' + formatNumber(bestMonth.amount);
    }
    
    function updateTable(data) {
        let html = '', totalDonations = 0, totalTransactions = 0, grandTotal = 0;
        
        data.forEach(item => {
            totalDonations += item.donations;
            totalTransactions += item.transactions;
            grandTotal += item.total;
        });
        
        const formatNumber = (num) => {
            return num.toLocaleString('en-IN', { maximumFractionDigits: 0 });
        };
        
        data.forEach(item => {
            let pct = grandTotal > 0 ? ((item.total / grandTotal) * 100).toFixed(1) : 0;
            html += `<tr style="border-bottom:1px solid #edf2f7;">
                <td style="padding:4px; font-size:0.6rem;">${item.month.substring(0,3)}</td>
                <td style="padding:4px; font-size:0.6rem; text-align:right; color:#f59e0b;">৳${formatNumber(item.donations)}</td>
                <td style="padding:4px; font-size:0.6rem; text-align:right; color:#2563eb;">৳${formatNumber(item.transactions)}</td>
                <td style="padding:4px; font-size:0.6rem; text-align:right; font-weight:500; color:#10b981;">৳${formatNumber(item.total)}</td>
                <td style="padding:4px; font-size:0.6rem; text-align:right;">${pct}%</td>
            </tr>`;
        });
        
        document.getElementById('monthlyDataTable').innerHTML = html;
        document.getElementById('tableFoot').style.display = 'table-footer-group';
        document.getElementById('totalDonationsFoot').innerHTML = '৳' + formatNumber(totalDonations);
        document.getElementById('totalTransactionsFoot').innerHTML = '৳' + formatNumber(totalTransactions);
        document.getElementById('totalGrandFoot').innerHTML = '৳' + formatNumber(grandTotal);
    }
    
    document.getElementById('exportChartBtn').addEventListener('click', function() {
        if (chart) {
            let link = document.createElement('a');
            link.download = `monthly-comparison-${document.getElementById('yearSelect').value}.png`;
            link.href = chart.toBase64Image();
            link.click();
        }
    });
});
</script>

<style>
.compact-card, .stat-micro { transition: all 0.2s ease; }
.compact-card:hover { transform: translateY(-1px); box-shadow: 0 4px 8px -2px rgba(0,0,0,0.05); }
.stat-micro:hover { transform: translateY(-1px); box-shadow: 0 4px 8px -2px rgba(0,0,0,0.1); }
[style*="overflow-y: auto"]::-webkit-scrollbar { width: 3px; }
[style*="overflow-y: auto"]::-webkit-scrollbar-track { background: #f1f5f9; }
[style*="overflow-y: auto"]::-webkit-scrollbar-thumb { background: #cbd5e0; border-radius: 3px; }
@media (max-width: 768px) { [style*="height: 300px"] { height: 250px !important; } }
</style>
@endsection