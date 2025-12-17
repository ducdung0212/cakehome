@extends('admin.layouts.master')

@section('title', 'Báo cáo - Doanh thu')

@section('breadcrumb')
    <li class="breadcrumb-item active">Báo cáo</li>
    <li class="breadcrumb-item active">Doanh thu</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="page-title">Báo cáo Doanh thu & Tài chính</h1>
                <p class="text-muted mb-0">Xem dòng tiền theo thời gian, phương thức thanh toán và AOV.</p>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.reports.revenue') }}" class="row g-2 align-items-end">
                    <div class="col-12 col-xl-6">
                        <label class="form-label">Bộ lọc nhanh</label>
                        <div class="d-flex flex-wrap gap-2">
                            @php
                                $quick = [
                                    'today' => 'Hôm nay',
                                    'week' => 'Tuần này',
                                    'month' => 'Tháng này',
                                    'quarter' => 'Quý này',
                                    'year' => 'Năm nay',
                                    'custom' => 'Tùy chọn ngày',
                                ];
                            @endphp
                            @foreach ($quick as $key => $label)
                                <button type="submit" name="range" value="{{ $key }}"
                                    class="btn btn-sm {{ ($range ?? 'month') === $key ? 'btn-primary' : 'btn-outline-primary' }}">
                                    {{ $label }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div class="col-6 col-xl-3">
                        <label class="form-label">Từ ngày</label>
                        <input type="date" class="form-control" name="start_date"
                            value="{{ optional($startAt)->toDateString() }}">
                    </div>

                    <div class="col-6 col-xl-3">
                        <label class="form-label">Đến ngày</label>
                        <input type="date" class="form-control" name="end_date"
                            value="{{ optional($endAt)->toDateString() }}">
                    </div>

                    <div class="col-12">
                        <small class="text-muted">Nếu chọn ngày thủ công, hãy bấm nút "Tùy chọn ngày" hoặc giữ nguyên và hệ
                            thống sẽ lấy theo khoảng đã chọn.</small>
                    </div>
                </form>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-xl-4 col-md-6">
                <div class="stats-card">
                    <div class="stats-icon bg-primary">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                    <div class="stats-content">
                        <div class="stats-label">Tổng doanh thu</div>
                        <div class="stats-value">{{ number_format($totalRevenue ?? 0, 0, ',', '.') }}₫</div>
                        <div class="text-muted">{{ optional($startAt)->format('d/m/Y') }} -
                            {{ optional($endAt)->format('d/m/Y') }}</div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6">
                <div class="stats-card">
                    <div class="stats-icon bg-success">
                        <i class="bi bi-receipt"></i>
                    </div>
                    <div class="stats-content">
                        <div class="stats-label">Số đơn đã ghi nhận doanh thu</div>
                        <div class="stats-value">{{ $paidOrdersCount ?? 0 }}</div>
                        <div class="text-muted">Dựa trên payment completed + paid_at</div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-12">
                <div class="stats-card">
                    <div class="stats-icon bg-warning">
                        <i class="bi bi-graph-up"></i>
                    </div>
                    <div class="stats-content">
                        <div class="stats-label">AOV (Giá trị đơn hàng trung bình)</div>
                        <div class="stats-value">{{ number_format($aov ?? 0, 0, ',', '.') }}₫</div>
                        <div class="text-muted">AOV = Tổng doanh thu / Số đơn</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-xl-8">
                <div class="card dashboard-chart-card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0">Doanh thu theo thời gian</h5>
                        <span class="text-muted">{{ ($range ?? 'month') === 'year' ? 'Theo tháng (nếu dài)' : '' }}</span>
                    </div>
                    <div class="card-body">
                        <div style="position: relative; height: 320px; width: 100%;">
                            <canvas id="revenueTimeChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                <div class="card dashboard-chart-card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Doanh thu theo phương thức thanh toán</h5>
                    </div>
                    <div class="card-body">
                        <div style="position: relative; height: 220px; width: 100%;">
                            <canvas id="paymentMethodChart"></canvas>
                        </div>

                        <div class="mt-3">
                            @php
                                $cash = $methods['cash'] ?? null;
                                $momo = $methods['momo'] ?? null;
                            @endphp
                            <div class="d-flex justify-content-between">
                                <span>{{ $cash['label'] ?? 'COD' }}</span>
                                <strong>{{ number_format($cash['pct'] ?? 0, 1) }}%</strong>
                            </div>
                            <div class="d-flex justify-content-between text-muted">
                                <small>{{ number_format($cash['amount'] ?? 0, 0, ',', '.') }}₫</small>
                                <small>{{ $cash['count'] ?? 0 }} payments</small>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <span>{{ $momo['label'] ?? 'MoMo' }}</span>
                                <strong>{{ number_format($momo['pct'] ?? 0, 1) }}%</strong>
                            </div>
                            <div class="d-flex justify-content-between text-muted">
                                <small>{{ number_format($momo['amount'] ?? 0, 0, ',', '.') }}₫</small>
                                <small>{{ $momo['count'] ?? 0 }} payments</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const timeLabels = {!! json_encode($timeLabels ?? []) !!};
        const timeValues = {!! json_encode($timeValues ?? []) !!};

        const methods = {!! json_encode($methods ?? []) !!};
        const methodLabels = Object.values(methods).map(m => m.label);
        const methodValues = Object.values(methods).map(m => Number(m.amount || 0));

        const ctx = document.getElementById('revenueTimeChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: timeLabels,
                datasets: [{
                    label: 'Doanh thu',
                    data: timeValues,
                    borderColor: 'rgb(99, 102, 241)',
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    tension: 0.35,
                    fill: true,
                    pointRadius: 3,
                    pointHoverRadius: 5,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function(ctx) {
                                const v = Number(ctx.raw || 0);
                                return ' ' + v.toLocaleString('vi-VN') + '₫';
                            }
                        }
                    }
                },
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return Number(value).toLocaleString('vi-VN') + '₫';
                            }
                        }
                    }
                }
            }
        });

        const ctx2 = document.getElementById('paymentMethodChart').getContext('2d');
        new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: methodLabels,
                datasets: [{
                    data: methodValues,
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(34, 197, 94, 0.8)',
                        'rgba(234, 179, 8, 0.8)',
                        'rgba(148, 163, 184, 0.8)'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(ctx) {
                                const v = Number(ctx.raw || 0);
                                const total = methodValues.reduce((a, b) => a + b, 0);
                                const pct = total > 0 ? Math.round((v / total) * 1000) / 10 : 0;
                                return ` ${ctx.label}: ${v.toLocaleString('vi-VN')}₫ (${pct}%)`;
                            }
                        }
                    }
                }
            }
        });
    </script>
@endpush
