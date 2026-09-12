@extends('layouts.admin')

@section('content')
<div class="content">
    <div class="row">
        {{-- Dashboard Cards --}}
        <div class="col-lg-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-header">Total Retailers</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $retailerCount }}</h5>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">Total Manufacturers</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $manufacturerCount }}</h5>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card text-white bg-warning mb-3">
                <div class="card-header">Total Customers</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $customerCount }}</h5>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card text-white bg-info mb-3">
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
            <div class="card mb-4">
                <div class="card-header">Order Status Overview</div>
                <div class="card-body">
                    <canvas id="orderChart" height="80"></canvas>
                </div>
            </div>
        </div>

        {{-- Product Stock Chart --}}
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header">
                    Product Stock History (Last 7 Days)
                    <select id="productDropdown" class="form-select float-end w-auto">
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
    <div class="mb-4">
        <label for="vendorDropdown">Select Vendor:</label>
        <select id="vendorDropdown" class="form-control">
            <option value="">All Vendors</option>
            @foreach($vendors as $vendor)
                <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
            @endforeach
        </select>
    </div>
    
    
    <canvas id="walletChart" height="100"></canvas>

    <div class="mb-4">
        <label for="incomeFilter">Income Range:</label>
        <select id="incomeFilter" class="form-control w-25">
            <option value="1month">This Month</option>
            <option value="today">Today</option>
            <option value="week">This Week</option>
            <option value="6months">Last 6 Months</option>
            <option value="1year">Last 1 Year</option>
        </select>
    </div>
    
    <canvas id="incomeChart" height="100"></canvas>
    
    
</div>
@endsection

@section('scripts')
@parent
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
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
            borderColor: '#' + Math.floor(Math.random() * 16777215).toString(16),
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