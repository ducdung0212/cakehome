<?php

namespace App\Services;

use App\Models\Voucher;
use App\Models\VoucherUsage;
use Illuminate\Support\Carbon;

class VoucherService
{
    /**
     * Validate voucher constraints and calculate discount amount.
     *
     * @return array{voucher: Voucher, discount: float}
     */
    public function validateAndCalculate(string $code, int $userId, float $subtotal): array
    {
        $voucher = Voucher::where('code', $code)->first();
        if (!$voucher) {
            throw new \RuntimeException('Mã giảm giá không tồn tại.');
        }

        return $this->validateAndCalculateForVoucher($voucher, $userId, $subtotal);
    }

    /**
     * Same validation but locks the voucher row for update.
     * Use inside a DB transaction.
     *
     * @return array{voucher: Voucher, discount: float}
     */
    public function validateAndCalculateForUpdate(string $code, int $userId, float $subtotal): array
    {
        $voucher = Voucher::where('code', $code)->lockForUpdate()->first();
        if (!$voucher) {
            throw new \RuntimeException('Mã giảm giá không tồn tại.');
        }

        return $this->validateAndCalculateForVoucher($voucher, $userId, $subtotal);
    }

    /**
     * @return array{voucher: Voucher, discount: float}
     */
    private function validateAndCalculateForVoucher(Voucher $voucher, int $userId, float $subtotal): array
    {
        if (!$voucher->is_active) {
            throw new \RuntimeException('Mã giảm giá đang bị tắt.');
        }

        $now = Carbon::now();
        if ($voucher->valid_from && Carbon::parse($voucher->valid_from)->gt($now)) {
            throw new \RuntimeException('Mã giảm giá chưa đến thời gian áp dụng.');
        }
        if ($voucher->valid_until && Carbon::parse($voucher->valid_until)->lt($now)) {
            throw new \RuntimeException('Mã giảm giá đã hết hạn.');
        }

        if ($subtotal < (float) $voucher->min_order_value) {
            throw new \RuntimeException('Đơn hàng chưa đạt giá trị tối thiểu để dùng mã giảm giá.');
        }

        if (!is_null($voucher->usage_limit) && (int) $voucher->used_count >= (int) $voucher->usage_limit) {
            throw new \RuntimeException('Mã giảm giá đã hết lượt sử dụng.');
        }

        if (!is_null($voucher->used_per_user_limit)) {
            $usedByUser = VoucherUsage::where('voucher_id', $voucher->id)
                ->where('user_id', $userId)
                ->count();
            if ($usedByUser >= (int) $voucher->used_per_user_limit) {
                throw new \RuntimeException('Bạn đã dùng mã giảm giá này quá số lần cho phép.');
            }
        }

        $discount = 0.0;
        if ($voucher->discount_type === 'percentage') {
            $discount = $subtotal * ((float) $voucher->discount_value) / 100.0;
            if (!is_null($voucher->max_discount)) {
                $discount = min($discount, (float) $voucher->max_discount);
            }
        } else {
            $discount = (float) $voucher->discount_value;
        }

        $discount = max(0.0, min($discount, $subtotal));

        return [
            'voucher' => $voucher,
            'discount' => $discount,
        ];
    }
}
