@extends('admin.layouts.master')

@section('title', 'Dashboard')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="page-title">Dashboard</h1>
                <p class="text-muted">Chào mừng bạn quay trở lại!</p>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="stats-card">
                    <div class="stats-icon bg-primary">
                        <i class="bi bi-currency-dollar"></i>
                    </div>
                    <div class="stats-content">
                        <div class="stats-label">Doanh Thu Hôm Nay</div>
                        <div class="stats-value">{{ number_format($revenueToday ?? 0, 0, ',', '.') }}₫</div>
                        @php
                            $revPct = $revenueTrendPct ?? 0;
                        @endphp
                        <div class="stats-trend {{ $revPct >= 0 ? 'text-success' : 'text-danger' }}">
                            <i class="bi {{ $revPct >= 0 ? 'bi-arrow-up' : 'bi-arrow-down' }}"></i>
                            {{ abs($revPct) }}% so với hôm qua
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="stats-card">
                    <div class="stats-icon bg-success">
                        <i class="bi bi-cart-check"></i>
                    </div>
                    <div class="stats-content">
                        <div class="stats-label">Đơn Hàng Mới</div>
                        <div class="stats-value">{{ $newOrdersToday ?? 0 }}</div>
                        @php
                            $ordPct = $ordersTrendPct ?? 0;
                        @endphp
                        <div class="stats-trend {{ $ordPct >= 0 ? 'text-success' : 'text-danger' }}">
                            <i class="bi {{ $ordPct >= 0 ? 'bi-arrow-up' : 'bi-arrow-down' }}"></i>
                            {{ abs($ordPct) }}% so với hôm qua
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="stats-card">
                    <div class="stats-icon bg-info">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="stats-content">
                        <div class="stats-label">Khách Hàng Mới</div>
                        <div class="stats-value">{{ $newCustomersToday ?? 0 }}</div>
                        @php
                            $cusPct = $customersTrendPct ?? 0;
                        @endphp
                        <div class="stats-trend {{ $cusPct >= 0 ? 'text-success' : 'text-danger' }}">
                            <i class="bi {{ $cusPct >= 0 ? 'bi-arrow-up' : 'bi-arrow-down' }}"></i>
                            {{ abs($cusPct) }}% so với hôm qua
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="stats-card">
                    <div class="stats-icon bg-warning">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <div class="stats-content">
                        <div class="stats-label">Sản Phẩm</div>
                        <div class="stats-value">{{ $productsCount ?? 0 }}</div>
                        <div class="stats-trend {{ ($lowStockCount ?? 0) > 0 ? 'text-danger' : 'text-success' }}">
                            <i
                                class="bi {{ ($lowStockCount ?? 0) > 0 ? 'bi-exclamation-triangle' : 'bi-check-circle' }}"></i>
                            {{ $lowStockCount ?? 0 }} sắp hết hàng
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-xl-8 mb-4 mb-xl-0">
                <div class="card dashboard-chart-card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Doanh Thu Theo Tháng</h5>
                    </div>
                    <div class="card-body">
                        <div style="position: relative; height: 300px; width: 100%;">
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                <div class="card dashboard-chart-card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Đơn Hàng Theo Trạng Thái</h5>
                    </div>
                    <div class="card-body">
                        <div style="position: relative; height: 300px; width: 100%;">
                            <canvas id="orderStatusChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-8 mb-4 mb-xl-0">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Đơn Hàng Gần Đây</h5>
                        <a href="/admin/orders" class="btn btn-sm btn-outline-primary">Xem tất cả</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Mã Đơn</th>
                                        <th>Khách Hàng</th>
                                        <th>Sản Phẩm</th>
                                        <th>Tổng Tiền</th>
                                        <th>Trạng Thái</th>
                                        <th>Thời Gian</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse (($recentOrders ?? []) as $order)
                                        @php
                                            $status = $order['status'] ?? '';
                                            $badge = 'bg-secondary';
                                            $label = $status;
                                            if ($status === 'pending') {
                                                $badge = 'bg-warning';
                                                $label = 'Chờ xác nhận';
                                            } elseif (in_array($status, ['confirmed', 'processing', 'ready'], true)) {
                                                $badge = 'bg-primary';
                                                $label = 'Đã xác nhận';
                                            } elseif (in_array($status, ['shipping', 'delivered'], true)) {
                                                $badge = 'bg-info';
                                                $label = 'Đang giao';
                                            } elseif ($status === 'completed') {
                                                $badge = 'bg-success';
                                                $label = 'Hoàn thành';
                                            } elseif ($status === 'cancelled') {
                                                $badge = 'bg-danger';
                                                $label = 'Đã hủy';
                                            }
                                        @endphp
                                        <tr>
                                            <td><a href="/admin/orders/{{ $order['id'] }}"
                                                    class="text-primary">#{{ $order['id'] }}</a></td>
                                            <td>{{ $order['customer'] }}</td>
                                            <td>{{ $order['product_summary'] }}</td>
                                            <td>{{ number_format($order['total_price'] ?? 0, 0, ',', '.') }}₫</td>
                                            <td><span class="badge {{ $badge }}">{{ $label }}</span></td>
                                            <td>{{ \Illuminate\Support\Carbon::parse($order['created_at'])->diffForHumans() }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">Chưa có đơn hàng</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Sản Phẩm Bán Chạy</h5>
                    </div>
                    <div class="card-body">
                        <div class="top-products">
                            @forelse (($topProducts ?? []) as $p)
                                <div class="product-item">
                                    <div class="product-rank">{{ $p['rank'] }}</div>
                                    @php
                                        $img = !empty($p['image'])
                                            ? asset('storage/' . $p['image'])
                                            : 'https://via.placeholder.com/50';
                                    @endphp
                                    <img src="{{ $img }}" alt="Product" class="product-img">
                                    <div class="product-info">
                                        <div class="product-name">{{ $p['name'] }}</div>
                                        <div class="product-sales text-muted">{{ $p['qty'] }} đã bán</div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-muted">Chưa có dữ liệu</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const revenueByMonth = {!! json_encode($revenueByMonth ?? array_fill(0, 12, 0)) !!};
        const orderStatusChartData = {!! json_encode($orderStatusChartData ?? [0, 0, 0, 0, 0]) !!};

        // --- REVENUE CHART (Đã fix maintainAspectRatio) ---
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10', 'T11', 'T12'],
                datasets: [{
                    label: 'Doanh Thu (triệu đồng)',
                    data: revenueByMonth,
                    borderColor: 'rgb(99, 102, 241)',
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 4, // Tăng size điểm để dễ hover
                    pointHoverRadius: 6,
                    pointHitRadius: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, // Quan trọng: Cho phép chart co giãn theo div cha
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value + 'M';
                            }
                        }
                    }
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                }
            }
        });

        // --- ORDER STATUS CHART (Đã fix maintainAspectRatio) ---
        const orderStatusCtx = document.getElementById('orderStatusChart').getContext('2d');
        new Chart(orderStatusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Chờ xác nhận', 'Đã xác nhận', 'Đang giao', 'Hoàn thành', 'Đã hủy'],
                datasets: [{
                    data: orderStatusChartData,
                    backgroundColor: [
                        'rgba(255, 193, 7, 0.8)',
                        'rgba(99, 102, 241, 0.8)',
                        'rgba(13, 202, 240, 0.8)',
                        'rgba(25, 135, 84, 0.8)',
                        'rgba(220, 53, 69, 0.8)'
                    ],
                    borderWidth: 1,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, // Quan trọng: Cho phép chart co giãn theo div cha
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }
                    }
                },
                layout: {
                    padding: {
                        top: 10,
                        bottom: 10
                    }
                }
            }
        });
    </script>
@endpush