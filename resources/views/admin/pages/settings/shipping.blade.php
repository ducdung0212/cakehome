@extends('admin.layouts.master')

@section('title', 'Cài đặt - Vận chuyển')

@section('breadcrumb')
    <li class="breadcrumb-item active">Cài đặt</li>
    <li class="breadcrumb-item active">Vận chuyển</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="page-title">Cài đặt - Vận chuyển</h1>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.settings.shipping.update') }}" method="POST">
                    @csrf

                    @php
                        $shippingFee = old('shipping_fee', $settings['shipping_fee'] ?? 30000);
                        $freeThreshold = old('free_shipping_threshold', $settings['free_shipping_threshold'] ?? 500000);
                    @endphp

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Phí vận chuyển (VND)</label>
                            <input type="number" min="0" step="1" class="form-control" name="shipping_fee"
                                value="{{ $shippingFee }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Miễn phí vận chuyển khi đơn từ (VND)</label>
                            <input type="number" min="0" step="1" class="form-control"
                                name="free_shipping_threshold" value="{{ $freeThreshold }}" required>
                        </div>

                        <div class="col-12">
                            <small class="text-muted">
                                Ví dụ: Phí ship {{ number_format((float) $shippingFee, 0, ',', '.') }} VND,
                                miễn phí khi đơn từ {{ number_format((float) $freeThreshold, 0, ',', '.') }} VND.
                            </small>
                        </div>

                        <div class="col-12 d-flex gap-2">
                            <button class="btn btn-primary" type="submit">Lưu</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
