@extends('layouts.admin')

@section('styles')
<style>
    :root{
        --canvas:#f1f3f8;
        --ink:#0f1b2d;
        --ink-soft:#28374f;
        --muted:#69758c;
        --line:#e2e6ee;
        --surface:#ffffff;
        --indigo:#3454d1;
        --teal:#0d9488;
        --amber:#c9820b;
        --coral:#d9483c;
    }

    .dash{max-width:1440px;margin:0 auto;font-variant-numeric:tabular-nums}

    /* ===================== Header ===================== */
    .dash-head{display:flex;justify-content:space-between;align-items:flex-end;gap:16px;margin:0 0 22px;flex-wrap:wrap}
    .dash-head h1{font:800 26px/1.2 'Manrope',sans-serif;margin:0;color:var(--ink);letter-spacing:-.01em}
    .dash-head p{margin:6px 0 0;color:var(--muted);font-size:13.5px}
    .dash-live{font-size:12px;color:var(--muted);display:flex;align-items:center;gap:7px}
    .dash-live b{width:6px;height:6px;border-radius:50%;background:var(--teal);display:inline-block;box-shadow:0 0 0 4px rgba(13,148,136,.15)}

    /* ===================== KPI ledger band ===================== */
    .kpi-band{
        background:var(--ink);border-radius:16px;display:flex;flex-wrap:wrap;
        margin-bottom:24px;overflow:hidden;box-shadow:0 14px 30px -14px rgba(15,27,45,.45);
    }
    .kpi{
        flex:1 1 220px;padding:20px 24px;position:relative;display:flex;align-items:center;gap:14px;
        border-bottom:2px solid var(--kpi-color);
    }
    .kpi + .kpi{border-left:1px solid rgba(255,255,255,.08)}
    .kpi-ring{
        width:40px;height:40px;border-radius:10px;flex-shrink:0;display:flex;align-items:center;justify-content:center;
        border:1.5px solid var(--kpi-color);color:var(--kpi-color);background:rgba(255,255,255,.03);font-size:16px;
    }
    .kpi-figure{min-width:0}
    .kpi-figure .n{font:800 26px/1.1 'Manrope',sans-serif;color:#fff}
    .kpi-figure .l{font-size:12px;color:#aab4c6;margin-top:3px}
    .kpi-r{--kpi-color:var(--indigo)}
    .kpi-t{--kpi-color:var(--teal)}
    .kpi-a{--kpi-color:var(--amber)}
    .kpi-c{--kpi-color:var(--coral)}

    /* ===================== Chart panels ===================== */
    .panel{
        background:var(--surface);border-radius:14px;margin-bottom:22px;overflow:hidden;
        border-top:3px solid var(--panel-color,var(--indigo));
        box-shadow:0 1px 0 var(--line),0 12px 26px -18px rgba(15,27,45,.25);
    }
    .panel-head{
        display:flex;align-items:flex-start;justify-content:space-between;gap:14px;flex-wrap:wrap;
        padding:18px 22px 14px;border-bottom:1px solid var(--line);
    }
    .panel-title{font:700 15px 'Manrope',sans-serif;color:var(--ink)}
    .panel-sub{font-size:12px;color:var(--muted);margin-top:3px}
    .panel-stat{text-align:right}
    .panel-stat .n{font:800 20px 'Manrope',sans-serif;color:var(--ink)}
    .panel-stat .l{font-size:11px;color:var(--muted);margin-top:2px}
    .panel-body{padding:18px 22px 20px}
    .panel canvas{max-height:270px}
    .panel-indigo{--panel-color:var(--indigo)}
    .panel-teal{--panel-color:var(--teal)}
    .panel-amber{--panel-color:var(--amber)}

    /* legend chips rendered above chart */
    .legend-row{display:flex;flex-wrap:wrap;gap:14px;padding:0 22px 14px;font-size:12px;color:var(--ink-soft)}
    .legend-row .chip{display:flex;align-items:center;gap:6px}
    .legend-row .chip i{width:8px;height:8px;border-radius:2px;display:inline-block}

    /* ledger-style select field (underline, not boxed) */
    .field-select{
        border:0;border-bottom:1.5px solid var(--line);background:transparent;
        font:600 12.5px 'DM Sans',sans-serif;color:var(--ink);padding:4px 20px 6px 2px;
        appearance:none;-webkit-appearance:none;cursor:pointer;
        background-image:url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="10" height="6" viewBox="0 0 10 6"><path d="M1 1l4 4 4-4" stroke="%2369758c" stroke-width="1.6" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg>');
        background-repeat:no-repeat;background-position:right 2px center;transition:border-color .2s;
    }
    .field-select:hover,.field-select:focus{outline:none;border-color:var(--panel-color,var(--indigo))}

    /* segmented control for income period */
    .segmented{display:flex;border:1px solid var(--line);border-radius:9px;padding:2px;gap:2px}
    .segmented button{
        border:0;background:transparent;font:600 11.5px 'DM Sans',sans-serif;color:var(--muted);
        padding:6px 11px;border-radius:7px;cursor:pointer;transition:background .15s,color .15s;
    }
    .segmented button.active{background:var(--ink);color:#fff}
    .segmented button:not(.active):hover{background:var(--canvas);color:var(--ink)}

    @media(max-width:767px){
        .dash-head{flex-direction:column;align-items:flex-start}
        .kpi{flex:1 1 50%;padding:16px 18px}
        .panel-head{flex-direction:column}
        .panel-stat{text-align:left}
    }
</style>
@endsection

@section('content')
<div class="content dash">

    <div class="dash-head">
        <div>
            <h1>Overview dashboard</h1>
            <p>Operations, inventory and business performance at a glance.</p>
        </div>
        <div class="dash-live"><b></b>Live business insights</div>
    </div>

    {{-- KPI ledger band --}}
    <div class="kpi-band">
        <div class="kpi kpi-r">
            <div class="kpi-ring"><i class="fa fa-store"></i></div>
            <div class="kpi-figure"><div class="n">{{ $retailerCount }}</div><div class="l">Retailers</div></div>
        </div>
        <div class="kpi kpi-t">
            <div class="kpi-ring"><i class="fa fa-industry"></i></div>
            <div class="kpi-figure"><div class="n">{{ $manufacturerCount }}</div><div class="l">Manufacturers</div></div>
        </div>
        <div class="kpi kpi-a">
            <div class="kpi-ring"><i class="fa fa-users"></i></div>
            <div class="kpi-figure"><div class="n">{{ $customerCount }}</div><div class="l">Customers</div></div>
        </div>
        <div class="kpi kpi-c">
            <div class="kpi-ring"><i class="fa fa-truck"></i></div>
            <div class="kpi-figure"><div class="n">{{ $supplierCount }}</div><div class="l">Suppliers</div></div>
        </div>
    </div>

    <div class="row">
        {{-- Order Chart --}}
        <div class="col-lg-7">
            <div class="panel panel-indigo">
                <div class="panel-head">
                    <div><div class="panel-title">Order activity</div><div class="panel-sub">Orders by status, over time</div></div>
                </div>
                <div id="orderLegend" class="legend-row"></div>
                <div class="panel-body"><canvas id="orderChart" height="82"></canvas></div>
            </div>
        </div>

        {{-- Product Stock Chart --}}
        <div class="col-lg-5">
            <div class="panel panel-teal">
                <div class="panel-head">
                    <div><div class="panel-title">Product stock</div><div class="panel-sub">Last 7 days, by product</div></div>
                    <select id="productDropdown" class="field-select">
                        <option value="">All products</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="panel-body"><canvas id="stockChart" height="82"></canvas></div>
            </div>
        </div>
    </div>

    <div class="panel panel-amber">
        <div class="panel-head">
            <div><div class="panel-title">Wallet requests</div><div class="panel-sub">Due amount by status, per month</div></div>
            <div style="display:flex;align-items:center;gap:18px">
                <div class="panel-stat"><div class="n" id="walletTotal">–</div><div class="l">Total due</div></div>
                <select id="vendorDropdown" class="field-select">
                    <option value="">All vendors</option>
                    @foreach($vendors as $vendor)
                        <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="panel-body"><canvas id="walletChart" height="95"></canvas></div>
    </div>

    <div class="panel panel-indigo">
        <div class="panel-head">
            <div><div class="panel-title">Income performance</div><div class="panel-sub">Revenue collected over the selected period</div></div>
            <div style="display:flex;align-items:center;gap:18px">
                <div class="panel-stat"><div class="n" id="incomeTotal">–</div><div class="l">Total income</div></div>
                <div class="segmented" id="incomeFilter">
                    <button data-value="today">Today</button>
                    <button data-value="week">Week</button>
                    <button data-value="1month" class="active">Month</button>
                    <button data-value="6months">6 months</button>
                    <button data-value="1year">Year</button>
                </div>
            </div>
        </div>
        <div class="panel-body"><canvas id="incomeChart" height="95"></canvas></div>
    </div>

</div>
@endsection

@section('scripts')
@parent
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    /* ===================== Shared chart theme ===================== */
    Chart.defaults.font.family = "'DM Sans', sans-serif";
    Chart.defaults.font.size = 12;
    Chart.defaults.color = '#69758c';
    Chart.defaults.animation.duration = 850;
    Chart.defaults.animation.easing = 'easeOutQuart';
    Chart.defaults.plugins.legend.display = false; // custom legends used instead
    Chart.defaults.plugins.title.display = false;
    Chart.defaults.elements.line.tension = 0.4;
    Chart.defaults.elements.line.borderWidth = 2.5;
    Chart.defaults.elements.point.radius = 0;
    Chart.defaults.elements.point.hoverRadius = 5;
    Chart.defaults.elements.point.hoverBorderWidth = 2;
    Chart.defaults.elements.point.hitRadius = 10;

    const tooltipTheme = {
        backgroundColor: '#0f1b2d',
        titleColor: '#fff',
        titleFont: { family: "'Manrope', sans-serif", size: 12.5, weight: '700' },
        bodyColor: '#dfe4ee',
        bodyFont: { family: "'DM Sans', sans-serif", size: 12 },
        padding: 12,
        cornerRadius: 9,
        displayColors: true,
        boxPadding: 5,
        usePointStyle: true,
        borderColor: 'rgba(255,255,255,.08)',
        borderWidth: 1
    };
    const gridTheme = { color: '#eef0f5', drawTicks: false, borderDash: [3, 4] };
    const noGrid = { display: false };
    const inr = new Intl.NumberFormat('en-IN', { maximumFractionDigits: 0 });

    function withAlpha(hex, alpha) {
        const c = hex.replace('#', '');
        const r = parseInt(c.substring(0, 2), 16), g = parseInt(c.substring(2, 4), 16), b = parseInt(c.substring(4, 6), 16);
        return `rgba(${r}, ${g}, ${b}, ${alpha})`;
    }
    function makeGradient(ctx, chartArea, hex) {
        const gradient = ctx.createLinearGradient(0, chartArea.top, 0, chartArea.bottom);
        gradient.addColorStop(0, withAlpha(hex, 0.26));
        gradient.addColorStop(1, withAlpha(hex, 0.0));
        return gradient;
    }
    function renderLegend(el, items) {
        el.innerHTML = items.map(i => `<span class="chip"><i style="background:${i.color}"></i>${i.label}</span>`).join('');
    }

    /* ===================== Income performance ===================== */
    let incomeChartInstance;
    const allIncomeData = @json($incomeData);
    const incomeColor = '#3454d1';

    function filterIncomeData(range) {
        const now = new Date();
        let cutoffDate;
        switch (range) {
            case 'today': cutoffDate = new Date(now.getFullYear(), now.getMonth(), now.getDate()); break;
            case 'week': cutoffDate = new Date(); cutoffDate.setDate(now.getDate() - 7); break;
            case '1month': cutoffDate = new Date(); cutoffDate.setMonth(now.getMonth() - 1); break;
            case '6months': cutoffDate = new Date(); cutoffDate.setMonth(now.getMonth() - 6); break;
            case '1year': cutoffDate = new Date(); cutoffDate.setFullYear(now.getFullYear() - 1); break;
            default: cutoffDate = new Date(); cutoffDate.setMonth(now.getMonth() - 1);
        }
        return allIncomeData.filter(item => new Date(item.date) >= cutoffDate)
            .sort((a, b) => new Date(a.date) - new Date(b.date));
    }

    function renderIncomeChart(range = '1month') {
        const filtered = filterIncomeData(range);
        const ctx = document.getElementById('incomeChart').getContext('2d');
        const labels = filtered.map(item => item.date);
        const data = filtered.map(item => item.amount);

        document.getElementById('incomeTotal').textContent = '₹' + inr.format(data.reduce((s, v) => s + parseFloat(v || 0), 0));

        if (incomeChartInstance) incomeChartInstance.destroy();

        incomeChartInstance = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Total income',
                    data: data,
                    borderColor: incomeColor,
                    backgroundColor: (context) => {
                        const { ctx, chartArea } = context.chart;
                        return chartArea ? makeGradient(ctx, chartArea, incomeColor) : null;
                    },
                    fill: true, tension: 0.4, borderWidth: 3,
                    pointBackgroundColor: '#fff', pointBorderColor: incomeColor,
                    pointHoverBackgroundColor: incomeColor, pointHoverBorderColor: '#fff'
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: true,
                interaction: { mode: 'index', intersect: false },
                plugins: { tooltip: tooltipTheme },
                scales: {
                    y: { beginAtZero: true, grid: gridTheme, border: { display: false }, ticks: { callback: v => '₹' + inr.format(v) } },
                    x: { grid: noGrid, border: { display: false } }
                }
            }
        });
    }

    document.querySelectorAll('#incomeFilter button').forEach(btn => {
        btn.addEventListener('click', function () {
            document.querySelectorAll('#incomeFilter button').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            renderIncomeChart(this.dataset.value);
        });
    });
    renderIncomeChart('1month');
</script>

<script>
    /* ===================== Wallet requests ===================== */
    const walletData = @json($walletRequestData);
    let walletChartInstance;
    const walletColors = { Approved: '#0d9488', Pending: '#c9820b', Rejected: '#d9483c', default: '#3454d1' };

    function formatMonth(month, year) {
        return new Date(year, month - 1).toLocaleString('default', { month: 'short', year: 'numeric' });
    }

    function groupWalletData(vendorId = null) {
        const filtered = vendorId ? walletData.filter(item => item.vendor_id == vendorId) : walletData;
        const grouped = {};
        const months = new Set();
        let total = 0;

        filtered.forEach(item => {
            const label = formatMonth(item.month, item.year);
            months.add(label);
            const status = item.status;
            if (!grouped[status]) grouped[status] = {};
            if (!grouped[status][label]) grouped[status][label] = 0;
            grouped[status][label] += parseFloat(item.total_due);
            total += parseFloat(item.total_due);
        });

        const sortedMonths = Array.from(months).sort((a, b) => new Date(a) - new Date(b));
        const datasets = Object.keys(grouped).map((status) => {
            const color = walletColors[status] || walletColors.default;
            return {
                label: status,
                data: sortedMonths.map(m => grouped[status][m] || 0),
                borderColor: color,
                backgroundColor: (context) => {
                    const { ctx, chartArea } = context.chart;
                    return chartArea ? makeGradient(ctx, chartArea, color) : null;
                },
                fill: true, tension: 0.4, borderWidth: 2.5,
                pointBackgroundColor: '#fff', pointBorderColor: color,
                pointHoverBackgroundColor: color, pointHoverBorderColor: '#fff'
            };
        });

        return { labels: sortedMonths, datasets, total };
    }

    function renderWalletChart(vendorId = null) {
        const ctx = document.getElementById('walletChart').getContext('2d');
        const { labels, datasets, total } = groupWalletData(vendorId);

        document.getElementById('walletTotal').textContent = '₹' + inr.format(total);

        if (walletChartInstance) walletChartInstance.destroy();

        walletChartInstance = new Chart(ctx, {
            type: 'line',
            data: { labels: labels, datasets: datasets },
            options: {
                responsive: true,
                interaction: { mode: 'index', intersect: false },
                plugins: { tooltip: tooltipTheme },
                scales: {
                    y: { beginAtZero: true, grid: gridTheme, border: { display: false }, ticks: { callback: v => '₹' + inr.format(v) } },
                    x: { grid: noGrid, border: { display: false } }
                }
            }
        });

        renderLegend(document.querySelector('.panel-amber .legend-row') || makeWalletLegendSlot(), datasets.map(d => ({ label: d.label, color: d.borderColor })));
    }

    // Wallet panel didn't originally have a legend row element — create one once, right under the header.
    function makeWalletLegendSlot() {
        let slot = document.querySelector('.panel-amber .legend-row');
        if (!slot) {
            slot = document.createElement('div');
            slot.className = 'legend-row';
            document.querySelector('.panel-amber .panel-head').insertAdjacentElement('afterend', slot);
        }
        return slot;
    }

    document.getElementById('vendorDropdown').addEventListener('change', function () {
        renderWalletChart(this.value);
    });
    renderWalletChart();
</script>

<script>
    /* ===================== Order activity + Product stock ===================== */
    const orderData = @json($orderData);
    const productStocks = @json($productStocks);
    const monthlyStockData = @json($monthlyStockData);
    let orderChartInstance;
    let stockChartInstance;
    const statusColorMap = { Pending: '#c9820b', Approved: '#0d9488', Rejected: '#d9483c', Completed: '#3454d1' };

    function renderOrderChart() {
        const ctx = document.getElementById('orderChart').getContext('2d');
        const labels = [...new Set(orderData.map(item => item.date))];
        const statuses = [...new Set(orderData.map(item => item.status))];

        const datasets = statuses.map(status => {
            const color = statusColorMap[status] || '#3454d1';
            return {
                label: status,
                data: labels.map(date => {
                    const found = orderData.find(item => item.date === date && item.status === status);
                    return found ? found.total : 0;
                }),
                borderColor: color, backgroundColor: color, fill: false, tension: 0.35, borderWidth: 2.5,
                pointBackgroundColor: '#fff', pointBorderColor: color,
                pointHoverBackgroundColor: color, pointHoverBorderColor: '#fff'
            };
        });

        renderLegend(document.getElementById('orderLegend'), statuses.map(s => ({ label: s, color: statusColorMap[s] || '#3454d1' })));

        if (orderChartInstance) orderChartInstance.destroy();

        orderChartInstance = new Chart(ctx, {
            type: 'line',
            data: { labels: labels, datasets: datasets },
            options: {
                responsive: true,
                interaction: { mode: 'index', intersect: false },
                plugins: { tooltip: tooltipTheme },
                scales: {
                    y: { beginAtZero: true, grid: gridTheme, border: { display: false } },
                    x: { grid: noGrid, border: { display: false } }
                }
            }
        });
    }

    function renderDefaultStockChart() {
        const ctx = document.getElementById('stockChart').getContext('2d');
        const stockColor = '#0d9488';

        if (stockChartInstance) stockChartInstance.destroy();

        stockChartInstance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: monthlyStockData.map(item => item.month),
                datasets: [{
                    label: 'Total stock quantity',
                    data: monthlyStockData.map(item => item.total_quantity),
                    backgroundColor: withAlpha(stockColor, 0.78),
                    hoverBackgroundColor: stockColor,
                    borderRadius: 7, borderSkipped: false, maxBarThickness: 30
                }]
            },
            options: {
                responsive: true,
                plugins: { tooltip: tooltipTheme },
                scales: {
                    x: { grid: noGrid, border: { display: false } },
                    y: { beginAtZero: true, grid: gridTheme, border: { display: false } }
                }
            }
        });
    }

    function getProductStockData(productId) {
        const product = productStocks.find(p => p.id == productId);
        const stockHistory = [];
        if (product && product.our_stocks) {
            product.our_stocks.forEach(stock => {
                stockHistory.push({ date: stock.created_at.split('T')[0], quantity: stock.quantity_available });
            });
        }
        return stockHistory;
    }

    function renderStockChart(productId) {
        if (!productId) { renderDefaultStockChart(); return; }

        const ctx = document.getElementById('stockChart').getContext('2d');
        const stockHistory = getProductStockData(productId);
        const color = '#0d9488';

        if (stockChartInstance) stockChartInstance.destroy();

        stockChartInstance = new Chart(ctx, {
            type: 'line',
            data: {
                labels: stockHistory.map(item => item.date),
                datasets: [{
                    label: 'Stock quantity',
                    data: stockHistory.map(item => item.quantity),
                    borderColor: color,
                    backgroundColor: (context) => {
                        const { ctx, chartArea } = context.chart;
                        return chartArea ? makeGradient(ctx, chartArea, color) : null;
                    },
                    fill: true, tension: 0.35, borderWidth: 2.5,
                    pointBackgroundColor: '#fff', pointBorderColor: color,
                    pointHoverBackgroundColor: color, pointHoverBorderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                interaction: { mode: 'index', intersect: false },
                plugins: { tooltip: tooltipTheme },
                scales: {
                    x: { grid: noGrid, border: { display: false } },
                    y: { beginAtZero: true, grid: gridTheme, border: { display: false } }
                }
            }
        });
    }

    document.getElementById('productDropdown').addEventListener('change', function () {
        renderStockChart(this.value);
    });

    renderOrderChart();
    renderDefaultStockChart();
</script>
@endsection