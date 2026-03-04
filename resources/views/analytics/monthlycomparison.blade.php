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
    <div class="row g-2 g-sm-3 mb-3">
        <div class="col-12">
            <div class="filter-card" style="background: white; border-radius: 16px; padding: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.02);">
                <div class="row g-2 align-items-end">
                    <div class="col-12 col-md-4">
                        <label class="form-label small text-muted mb-1">Select Year</label>
                        <select id="yearSelect" class="form-select form-select-sm" style="border-radius: 12px; padding: 8px 12px;">
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
                        <button id="updateChartBtn" class="btn w-100" style="background: linear-gradient(145deg, #2563eb, #1d4ed8); color: white; border: none; border-radius: 12px; padding: 8px 16px; font-weight: 500;">
                            <i class="fas fa-chart-line me-2"></i> Update Chart
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Card -->
    <div class="row g-2 g-sm-3 mb-3">
        <div class="col-12">
            <div class="content-card" style="background: white; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
                <div class="px-3 py-3" style="background: linear-gradient(145deg, #fafcff, #ffffff); border-bottom: 1px solid rgba(226, 232, 240, 0.6);">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0" style="font-weight: 600; color: #0b1e33; font-size: 1rem;">
                            <i class="fas fa-chart-bar me-2" style="color: #8b5cf6;"></i>
                            Monthly Revenue Comparison - <span id="selectedYearDisplay">{{ date('Y') }}</span>
                        </h6>
                        <div class="d-flex gap-3">
                            <div class="d-flex align-items-center">
                                <span style="width: 12px; height: 12px; background: #f59e0b; border-radius: 4px; margin-right: 6px;"></span>
                                <span style="font-size: 0.7rem; color: #64748b;">Donations</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <span style="width: 12px; height: 12px; background: #2563eb; border-radius: 4px; margin-right: 6px;"></span>
                                <span style="font-size: 0.7rem; color: #64748b;">Transactions</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <span style="width: 12px; height: 12px; background: #10b981; border-radius: 4px; margin-right: 6px;"></span>
                                <span style="font-size: 0.7rem; color: #64748b;">Total</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-3 p-sm-4">
                    <!-- Chart Container -->
                    <div style="position: relative; height: 400px; width: 100%;">
                        <canvas id="monthlyComparisonChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row g-2 g-sm-3 mb-3">
        <div class="col-6 col-md-3">
            <div class="stat-card p-3" style="background: linear-gradient(145deg, #f59e0b, #d97706); border-radius: 16px; box-shadow: 0 8px 16px -8px rgba(245,158,11,0.3);">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div style="width: 32px; height: 32px; background: rgba(255,255,255,0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-hand-holding-heart text-white" style="font-size: 0.9rem;"></i>
                    </div>
                    <span class="text-white opacity-75" style="font-size: 0.65rem;">TOTAL DONATIONS</span>
                </div>
                <h4 class="text-white mb-0 total-donations" style="font-size: 1.3rem; font-weight: 700;">৳0</h4>
                <small class="text-white opacity-75" id="donationsYearLabel">{{ date('Y') }}</small>
            </div>
        </div>
        
        <div class="col-6 col-md-3">
            <div class="stat-card p-3" style="background: linear-gradient(145deg, #2563eb, #1d4ed8); border-radius: 16px; box-shadow: 0 8px 16px -8px rgba(37,99,235,0.3);">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div style="width: 32px; height: 32px; background: rgba(255,255,255,0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-exchange-alt text-white" style="font-size: 0.9rem;"></i>
                    </div>
                    <span class="text-white opacity-75" style="font-size: 0.65rem;">TOTAL TRANSACTIONS</span>
                </div>
                <h4 class="text-white mb-0 total-transactions" style="font-size: 1.3rem; font-weight: 700;">৳0</h4>
                <small class="text-white opacity-75" id="transactionsYearLabel">{{ date('Y') }}</small>
            </div>
        </div>
        
        <div class="col-6 col-md-3">
            <div class="stat-card p-3" style="background: linear-gradient(145deg, #10b981, #059669); border-radius: 16px; box-shadow: 0 8px 16px -8px rgba(16,185,129,0.3);">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div style="width: 32px; height: 32px; background: rgba(255,255,255,0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-coins text-white" style="font-size: 0.9rem;"></i>
                    </div>
                    <span class="text-white opacity-75" style="font-size: 0.65rem;">GRAND TOTAL</span>
                </div>
                <h4 class="text-white mb-0 grand-total" style="font-size: 1.3rem; font-weight: 700;">৳0</h4>
                <small class="text-white opacity-75" id="totalYearLabel">{{ date('Y') }}</small>
            </div>
        </div>
        
        <div class="col-6 col-md-3">
            <div class="stat-card p-3" style="background: linear-gradient(145deg, #8b5cf6, #7c3aed); border-radius: 16px; box-shadow: 0 8px 16px -8px rgba(139,92,246,0.3);">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div style="width: 32px; height: 32px; background: rgba(255,255,255,0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-calendar text-white" style="font-size: 0.9rem;"></i>
                    </div>
                    <span class="text-white opacity-75" style="font-size: 0.65rem;">BEST MONTH</span>
                </div>
                <h4 class="text-white mb-0 best-month-name" style="font-size: 1rem; font-weight: 600;">January</h4>
                <small class="text-white opacity-75 best-month-amount">৳0</small>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="row g-2 g-sm-3">
        <div class="col-12">
            <div class="content-card" style="background: white; border-radius: 16px; overflow: hidden;">
                <div class="px-3 py-2" style="background: linear-gradient(145deg, #fafcff, #ffffff); border-bottom: 1px solid rgba(226, 232, 240, 0.6);">
                    <h6 class="mb-0" style="font-weight: 600; color: #0b1e33; font-size: 0.9rem;">
                        <i class="fas fa-table me-2" style="color: #2563eb;"></i>
                        Monthly Breakdown - <span id="tableYearLabel">{{ date('Y') }}</span>
                    </h6>
                </div>
                <div class="p-3">
                    <div class="table-responsive">
                        <table class="table table-sm" style="margin-bottom: 0;">
                            <thead>
                                <tr style="background: #f8fafc;">
                                    <th style="padding: 8px; font-size: 0.7rem;">Month</th>
                                    <th style="padding: 8px; font-size: 0.7rem;">Donations</th>
                                    <th style="padding: 8px; font-size: 0.7rem;">Transactions</th>
                                    <th style="padding: 8px; font-size: 0.7rem;">Total</th>
                                    <th style="padding: 8px; font-size: 0.7rem;">% of Year</th>
                                </tr>
                            </thead>
                            <tbody id="monthlyDataTable">
                                <tr>
                                    <td colspan="5" class="text-center py-3">
                                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <span class="ms-2" style="font-size: 0.8rem;">Loading data...</span>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot id="tableFoot" style="display: none;">
                                <tr style="background: #f8fafc; border-top: 2px solid #e2e8f0;">
                                    <th style="padding: 8px; font-size: 0.7rem;">Total</th>
                                    <th style="padding: 8px; font-size: 0.7rem;" id="totalDonationsFoot">৳0</th>
                                    <th style="padding: 8px; font-size: 0.7rem;" id="totalTransactionsFoot">৳0</th>
                                    <th style="padding: 8px; font-size: 0.7rem;" id="totalGrandFoot">৳0</th>
                                    <th style="padding: 8px; font-size: 0.7rem;">100%</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
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
    const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    
    // Load initial data
    loadData({{ date('Y') }});
    
    // Update button click
    document.getElementById('updateChartBtn').addEventListener('click', function() {
        const year = document.getElementById('yearSelect').value;
        document.getElementById('selectedYearDisplay').textContent = year;
        document.getElementById('donationsYearLabel').textContent = year;
        document.getElementById('transactionsYearLabel').textContent = year;
        document.getElementById('totalYearLabel').textContent = year;
        document.getElementById('tableYearLabel').textContent = year;
        loadData(year);
    });
    
    function loadData(year) {
    // Show loading state
    document.getElementById('monthlyDataTable').innerHTML = `
        <tr>
            <td colspan="5" class="text-center py-3">
                <div class="spinner-border spinner-border-sm text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <span class="ms-2" style="font-size: 0.8rem;">Loading data...</span>
            </td>
        </tr>
    `;
    document.getElementById('tableFoot').style.display = 'none';
    
    const url = `{{ route('analytics.get-monthly-data') }}?year=${year}`;
    console.log('Fetching from:', url); // Debug log
    
    fetch(url)
        .then(response => {
            console.log('Response status:', response.status); // Debug log
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Data received:', data); // Debug log
            updateChart(data);
            updateStats(data);
            updateTable(data);
        })
        .catch(error => {
            console.error('Error loading data:', error);
            document.getElementById('monthlyDataTable').innerHTML = `
                <tr>
                    <td colspan="5" class="text-center py-3 text-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>Error: ${error.message}
                    </td>
                </tr>
            `;
        });
}
    
    function updateChart(data) {
        const months = data.map(item => item.month.substring(0, 3));
        const donations = data.map(item => item.donations);
        const transactions = data.map(item => item.transactions);
        const totals = data.map(item => item.total);
        
        if (chart) {
            chart.destroy();
        }
        
        chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [
                    {
                        label: 'Donations',
                        data: donations,
                        backgroundColor: '#f59e0b',
                        borderRadius: 6,
                        barPercentage: 0.7,
                    },
                    {
                        label: 'Transactions',
                        data: transactions,
                        backgroundColor: '#2563eb',
                        borderRadius: 6,
                        barPercentage: 0.7,
                    },
                    {
                        label: 'Total',
                        data: totals,
                        type: 'line',
                        borderColor: '#10b981',
                        backgroundColor: 'transparent',
                        borderWidth: 3,
                        pointBackgroundColor: '#10b981',
                        pointBorderColor: 'white',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        tension: 0.1,
                        yAxisID: 'y',
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: 'white',
                        titleColor: '#1e293b',
                        bodyColor: '#64748b',
                        borderColor: '#e2e8f0',
                        borderWidth: 1,
                        padding: 10,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ৳';
                                }
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat().format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f1f5f9',
                        },
                        ticks: {
                            callback: function(value) {
                                return '৳' + value.toLocaleString();
                            },
                            font: {
                                size: 10
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 10
                            }
                        }
                    }
                }
            }
        });
    }
    
    function updateStats(data) {
        let totalDonations = 0;
        let totalTransactions = 0;
        let grandTotal = 0;
        let bestMonth = { name: '', amount: 0 };
        
        data.forEach(item => {
            totalDonations += item.donations;
            totalTransactions += item.transactions;
            grandTotal += item.total;
            
            if (item.total > bestMonth.amount) {
                bestMonth = { name: item.month, amount: item.total };
            }
        });
        
        document.querySelector('.total-donations').textContent = '৳' + totalDonations.toLocaleString();
        document.querySelector('.total-transactions').textContent = '৳' + totalTransactions.toLocaleString();
        document.querySelector('.grand-total').textContent = '৳' + grandTotal.toLocaleString();
        document.querySelector('.best-month-name').textContent = bestMonth.name;
        document.querySelector('.best-month-amount').textContent = '৳' + bestMonth.amount.toLocaleString();
    }
    
    function updateTable(data) {
        let tableHtml = '';
        let totalDonations = 0;
        let totalTransactions = 0;
        let grandTotal = 0;
        
        data.forEach(item => {
            totalDonations += item.donations;
            totalTransactions += item.transactions;
            grandTotal += item.total;
            
            const percentage = grandTotal > 0 ? ((item.total / grandTotal) * 100).toFixed(1) : 0;
            
            tableHtml += `
                <tr>
                    <td style="padding: 6px 8px; font-size: 0.75rem; font-weight: 500;">${item.month}</td>
                    <td style="padding: 6px 8px; font-size: 0.75rem; color: #f59e0b;">৳${item.donations.toLocaleString()}</td>
                    <td style="padding: 6px 8px; font-size: 0.75rem; color: #2563eb;">৳${item.transactions.toLocaleString()}</td>
                    <td style="padding: 6px 8px; font-size: 0.75rem; font-weight: 600; color: #10b981;">৳${item.total.toLocaleString()}</td>
                    <td style="padding: 6px 8px; font-size: 0.75rem;">${percentage}%</td>
                </tr>
            `;
        });
        
        document.getElementById('monthlyDataTable').innerHTML = tableHtml;
        document.getElementById('tableFoot').style.display = 'table-footer-group';
        document.getElementById('totalDonationsFoot').innerHTML = '৳' + totalDonations.toLocaleString();
        document.getElementById('totalTransactionsFoot').innerHTML = '৳' + totalTransactions.toLocaleString();
        document.getElementById('totalGrandFoot').innerHTML = '৳' + grandTotal.toLocaleString();
    }
    
    // Export chart as image
    document.getElementById('exportChartBtn').addEventListener('click', function() {
        if (chart) {
            const link = document.createElement('a');
            link.download = `monthly-comparison-${document.getElementById('yearSelect').value}.png`;
            link.href = chart.toBase64Image();
            link.click();
        }
    });
});
</script>

<style>
.stat-card {
    transition: all 0.2s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 24px -8px rgba(0,0,0,0.2) !important;
}

@media (max-width: 768px) {
    .stat-card h4 {
        font-size: 1.1rem !important;
    }
    
    [style*="height: 400px"] {
        height: 300px !important;
    }
}
</style>
@endsection