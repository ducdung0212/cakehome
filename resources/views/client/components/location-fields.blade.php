@push('script')
    <script src="{{ asset('assets/client/js/location-cascade.js') }}"></script>
@endpush
@props([
    'prefix' => '',
    'provinceValue' => '',
    'districtValue' => '',
    'wardValue' => '',
    'required' => true,
])

@php
    $provinceId = $prefix ? $prefix . '-province' : 'province';
    $districtId = $prefix ? $prefix . '-district' : 'district';
    $wardId = $prefix ? $prefix . '-ward' : 'ward';
    // Always use standard names for form submission
    $provinceName = 'province';
    $districtName = 'district';
    $wardName = 'ward';
@endphp

<div class="mb-3">
    <label class="form-label">Tỉnh/Thành phố @if ($required)
            <span class="text-danger">*</span>
        @endif
    </label>
    <select class="form-select location-province" id="{{ $provinceId }}" name="{{ $provinceName }}"
        data-current="{{ $provinceValue }}" {{ $required ? 'required' : '' }}>
        <option value="TP. Hồ Chí Minh" {{ $provinceValue == 'TP. Hồ Chí Minh' ? 'selected' : '' }}>TP. Hồ Chí Minh
        </option>
    </select>
</div>

<div class="mb-3">
    <label class="form-label">Quận/Huyện @if ($required)
            <span class="text-danger">*</span>
        @endif
    </label>
    <select class="form-select location-district" id="{{ $districtId }}" name="{{ $districtName }}"
        data-current="{{ $districtValue }}" {{ $required ? 'required' : '' }}>
        <option value="">Chọn...</option>
    </select>
</div>

<div class="mb-3">
    <label class="form-label">Phường/Xã @if ($required)
            <span class="text-danger">*</span>
        @endif
    </label>
    <select class="form-select location-ward" id="{{ $wardId }}" name="{{ $wardName }}"
        data-current="{{ $wardValue }}" {{ $required ? 'required' : '' }}>
        <option value="">Chọn...</option>
    </select>
</div>
