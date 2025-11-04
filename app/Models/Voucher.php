<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $fillable=[
        'code',
        'discount_type',
        'discount_value',
        'min_order_value',
        'max_discount',
        'usage_limit',
        'used_count',
        'used_per_user_limit',
        'valid_from',
        'valid_until',
        'is_active'
    ];
    public function usages()
    {
        return $this->hasMany(VoucherUsage::class);
    }
    protected $casts = [
    'discount_value' => 'decimal:2',
    'min_order_value' => 'decimal:2',
    'max_discount' => 'decimal:2',
    'usage_limit' => 'integer',
    'used_count' => 'integer',
    'used_per_user_limit' => 'integer',
    'valid_from' => 'datetime',
    'valid_until' => 'datetime',
    'is_active' => 'boolean'
];
}
