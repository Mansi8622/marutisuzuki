@extends('layouts.admin')

@section('styles')
<style>
    .dashboard-page{max-width:1440px;margin:auto}.dashboard-welcome{display:flex;justify-content:space-between;align-items:end;margin:0 0 24px}.dashboard-welcome h1{font:800 27px 'Manrope',sans-serif;margin:0;color:#16243b}.dashboard-welcome p{margin:7px 0 0;color:#667085}.dashboard-date{font-size:12px;color:#667085;background:#fff;border:1px solid #e8edf4;padding:9px 12px;border-radius:8px}.metric-card{position:relative;overflow:hidden;border:0!important;border-radius:14px!important;padding:20px!important;min-height:140px;box-shadow:0 8px 24px rgba(16,24,40,.06);background:#fff!important;color:#16243b!important;transition:transform .2s,box-shadow .2s}.metric-card:hover{transform:translateY(-4px);box-shadow:0 16px 30px rgba(16,24,40,.1)}.metric-card:after{content:'';position:absolute;width:100px;height:100px;border-radius:50%;right:-30px;bottom:-40px;background:var(--metric-color);opacity:.1}.metric-card .metric-icon{width:43px;height:43px;border-radius:12px;display:flex;align-items:center;justify-content:center;background:#edf2ff;background:color-mix(in srgb,var(--metric-color) 14%,white);color:var(--metric-color);font-size:18px}.metric-card h5{font:800 30px 'Manrope',sans-serif;margin:13px 0 2px}.metric-card .card-header{background:none!important;border:0!important;padding:0!important;color:#667085;font-size:13px}.metric-blue{--metric-color:#4169e1}.metric-green{--metric-color:#18ae85}.metric-orange{--metric-color:#f79009}.metric-purple{--metric-color:#8b5cf6}.chart-panel{border:0!important;border-radius:14px!important;box-shadow:0 8px 24px rgba(16,24,40,.06);background:#fff!important;margin-bottom:22px;overflow:hidden}.chart-panel .card-header{background:#fff!important;border-bottom:1px solid #edf0f5!important;padding:17px 20px!important;font:700 15px 'Manrope',sans-serif}.chart-panel .card-body{padding:18px 20px!important}.dashboard-filter{border:1px solid #dce3ed;border-radius:7px;padding:7px 10px;font-size:12px;color:#46556b;background:#fff}.chart-panel canvas{max-height:280px}@media(max-width:767px){.dashboard-welcome{align-items:start;gap:12px;flex-direction:column}.dashboard-date{display:none}}
</style>
@endsection

@section('content')
<div class="content dashboard-page">
    <div class="dashboard-welcome"><div><h1>Overview dashboard</h1><p>Track your operations, inventory and business performance.</p></div><div class="dashboard-date"><i class="fa fa-circle" style="color:#18ae85"></i> Live business insights</div></div>
    <div class="row">
        {{-- Dashboard Cards --}}
        <div class="col-lg-3">
            <div class="card metric-card metric-blue mb-3"><i class="fa fa-store metric-icon"></i>
                <div class="card-header">Total Retailers</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $retailerCount }}</h5>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card metric-card metric-green mb-3"><i class="fa fa-industry metric-icon"></i>
                <div class="card-header">Total Manufacturers</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $manufacturerCount }}</h5>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card metric-card metric-orange mb-3"><i class="fa fa-users metric-icon"></i>
                <div class="card-header">Total Customers</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $customerCount }}</h5>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card metric-card metric-purple mb-3"><i class="fa fa-truck metric-icon"></i>
                <div class="card-header">Total Suppliers</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $supplierCount }}</h5>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Order Chart --}}
        <div class="col-lg-6">
            <div class="card chart-panel mb-4">
                <div class="card-header">Order activity <span class="pull-right text-muted" style="font:400 12px 'DM Sans'">Status trend</span></div>
                <div class="card-body">
                    <canvas id="orderChart" height="80"></canvas>
                </div>
            </div>
        </div>

        {{-- Product Stock Chart --}}
        <div class="col-lg-6">
            <div class="card chart-panel mb-4">
                <div class="card-header">
                    Product Stock History (Last 7 Days)
                    <select id="productDropdown" class="dashboard-filter pull-right">
                        <option value="">Select Product</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="card-body">
                    <canvas id="stockChart" height="80"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="card chart-panel"><div class="card-header">Wallet requests <select id="vendorDropdown" class="dashboard-filter pull-right">
            <option value="">All Vendors</option>
            @foreach($vendors as $vendor)
                <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
            @endforeach
        </select></div><div class="card-body"><canvas id="walletChart" height="100"></canvas></div></div>

    <div class="card chart-panel"><div class="card-header">Income performance <select id="incomeFilter" class="dashboard-filter pull-right">
            <option value="1month">This Month</option>
            <option value="today">Today</option>
            <option value="week">This Week</option>
            <option value="6months">Last 6 Months</option>
            <option value="1year">Last 1 Year</option>
        </select></div><div class="card-body"><canvas id="incomeChart" height="100"></canvas></div></div>
    
    
</div>
@endsection

@section('scripts')
@parent
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    Chart.defaults.font.family = "DM Sans, sans-serif";
    Chart.defaults.color = '#667085';
    Chart.defaults.animation.duration = 1100;
    Chart.defaults.animation.easing = 'easeOutQuart';
    Chart.defaults.plugins.legend.labels.usePointStyle = true;
    Chart.defaults.plugins.legend.labels.boxWidth = 8;
    Chart.defaults.plugins.title.display = false;
    Chart.defaults.elements.line.tension = 0.4;
    Chart.defaults.elements.line.borderWidth = 3;
    Chart.defaults.elements.point.radius = 3;
    let incomeChartInstance;
    const allIncomeData = @json($incomeData);

    function filterIncomeData(range) {
        const now = new Date();
        let cutoffDate;

        switch (range) {
            case 'today':
                cutoffDate = new Date(now.getFullYear(), now.getMonth(), now.getDate());
                break;
            case 'week':
                cutoffDate = new Date();
                cutoffDate.setDate(now.getDate() - 7);
                break;
            case '1month':
                cutoffDate = new Date();
                cutoffDate.setMonth(now.getMonth() - 1);
                break;
            case '6months':
                cutoffDate = new Date();
                cutoffDate.setMonth(now.getMonth() - 6);
                break;
            case '1year':
                cutoffDate = new Date();
                cutoffDate.setFullYear(now.getFullYear() - 1);
                break;
            default:
                cutoffDate = new Date();
                cutoffDate.setMonth(now.getMonth() - 1);
        }

        return allIncomeData.filter(item => {
            return new Date(item.date) >= cutoffDate;
        }).sort((a, b) => new Date(a.date) - new Date(b.date));
    }

    function renderIncomeChart(range = '1month') {
        const filtered = filterIncomeData(range);

        const ctx = document.getElementById('incomeChart').getContext('2d');
        const labels = filtered.map(item => item.date);
        const data = filtered.map(item => item.amount);

        if (incomeChartInstance) incomeChartInstance.destroy();

        incomeChartInstance = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Total Income (₹)',
                    data: data,
                    borderColor: 'rgba(255, 205, 86, 1)',
                    backgroundColor: 'rgba(255, 205, 86, 0.3)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                plugins: {
                    title: {
                        display: true,
                        text: 'Income Overview'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Amount (₹)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Date'
                        }
                    }
                }
            }
        });
    }

    document.getElementById('incomeFilter').addEventListener('change', function () {
        renderIncomeChart(this.value);
    });

    // Default: 1 Month
    renderIncomeChart('1month');
</script>

<script>
    const walletData = @json($walletRequestData);
    let walletChartInstance;

    function formatMonth(month, year) {
        return new Date(year, month - 1).toLocaleString('default', { month: 'short', year: 'numeric' });
    }

    function groupWalletData(vendorId = null) {
        const filtered = vendorId
            ? walletData.filter(item => item.vendor_id == vendorId)
            : walletData;

        const grouped = {};
        const months = new Set();

        filtered.forEach(item => {
            const label = formatMonth(item.month, item.year);
            months.add(label);
            const status = item.status;

            if (!grouped[status]) grouped[status] = {};
            if (!grouped[status][label]) grouped[status][label] = 0;
            grouped[status][label] += parseFloat(item.total_due);
        });

        const sortedMonths = Array.from(months).sort((a, b) => new Date(a) - new Date(b));

        const datasets = Object.keys(grouped).map((status) => ({
            label: status,
            data: sortedMonths.map(month => grouped[status][month] || 0),
            borderColor: getStatusColor(status),
            backgroundColor: getStatusColor(status, 0.3),
            fill: true,
            tension: 0.4
        }));

        return { labels: sortedMonths, datasets };
    }

    function getStatusColor(status, alpha = 1) {
        const colors = {
            Approved: `rgba(75, 192, 192, ${alpha})`,
            Pending: `rgba(255, 159, 64, ${alpha})`,
            Rejected: `rgba(255, 99, 132, ${alpha})`,
            default: `rgba(153, 102, 255, ${alpha})`
        };
        return colors[status] || colors['default'];
    }

    function renderWalletChart(vendorId = null) {
        const ctx = document.getElementById('walletChart').getContext('2d');
        const { labels, datasets } = groupWalletData(vendorId);

        if (walletChartInstance) walletChartInstance.destroy();

        walletChartInstance = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: datasets
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: vendorId ? 'Vendor-wise Due by Status' : 'Total Due by Status (All Vendors)'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Due Amount'
                        }
                    }
                }
            }
        });
    }

    document.getElementById('vendorDropdown').addEventListener('change', function () {
        const vendorId = this.value;
        renderWalletChart(vendorId);
    });

    // Initial Render
    renderWalletChart();
</script>



<script>
    const orderData = @json($orderData);
    const productStocks = @json($productStocks);
    const monthlyStockData = @json($monthlyStockData);
    let orderChartInstance;
    let stockChartInstance;

    // Render Order Chart
    function renderOrderChart() {
        const ctx = document.getElementById('orderChart').getContext('2d');
        const labels = [...new Set(orderData.map(item => item.date))];
        const statuses = [...new Set(orderData.map(item => item.status))];

        const datasets = statuses.map(status => ({
            label: status,
            data: labels.map(date => {
                const found = orderData.find(item => item.date === date && item.status === status);
                return found ? found.total : 0;
            }),
            borderColor: ({ Pending: '#f79009', Approved: '#18ae85', Rejected: '#ef476f', Completed: '#4169e1' })[status] || '#8b5cf6',
            fill: false,
            tension: 0.3
        }));

        if (orderChartInstance) orderChartInstance.destroy();

        orderChartInstance = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: datasets
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Orders by Status Over Time'
                    }
                }
            }
        });
    }

    // Render Default Monthly Stock Chart
    function renderDefaultStockChart() {
        const ctx = document.getElementById('stockChart').getContext('2d');

        if (stockChartInstance) stockChartInstance.destroy();

        stockChartInstance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: monthlyStockData.map(item => item.month),
                datasets: [{
                    label: 'Total Stock Quantity',
                    data: monthlyStockData.map(item => item.total_quantity),
                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                plugins: {
                    title: {
                        display: true,
                        text: 'Monthly Total Stock Quantity'
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Month'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Quantity Available'
                        }
                    }
                }
            }
        });
    }

    // Get Specific Product Stock History
    function getProductStockData(productId) {
        const product = productStocks.find(p => p.id == productId);
        const stockHistory = [];

        if (product && product.our_stocks) {
            product.our_stocks.forEach(stock => {
                stockHistory.push({
                    date: stock.created_at.split('T')[0],
                    quantity: stock.quantity_available
                });
            });
        }

        return stockHistory;
    }

    // Render Stock Chart for Specific Product
    function renderStockChart(productId) {
        if (!productId) {
            renderDefaultStockChart();
            return;
        }

        const ctx = document.getElementById('stockChart').getContext('2d');
        const stockHistory = getProductStockData(productId);

        if (stockChartInstance) stockChartInstance.destroy();

        stockChartInstance = new Chart(ctx, {
            type: 'line',
            data: {
                labels: stockHistory.map(item => item.date),
                datasets: [{
                    label: 'Stock Quantity',
                    data: stockHistory.map(item => item.quantity),
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                plugins: {
                    title: {
                        display: true,
                        text: 'Daily Stock Quantity (Product)'
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Date'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Quantity Available'
                        }
                    }
                }
            }
        });
    }

    // Event Listener for Dropdown Change
    document.getElementById('productDropdown').addEventListener('change', function () {
        const selectedProductId = this.value;
        renderStockChart(selectedProductId);
    });

    // Initial Render
    renderOrderChart();
    renderDefaultStockChart();
</script>
@endsection
