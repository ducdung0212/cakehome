<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable=[
        'user_id',
        'total_price',
        'subtotal_price',
        'status',
        'shipping_address_id',
        'delivery_method',
        'delivery_date',
        'delivery_time',
        'voucher_id',
        'discount_amount',
        'notes'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function shippingAddress()
    {
        return $this->belongsTo(ShippingAddress::class);
    }
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
    public function orderStatusHistories()
    {
        return $this->hasMany(OrderStatusHistory::class);
    }
    public function voucherUsages()
    {
        return $this->hasMany(VoucherUsage::class);
    }
    protected $casts = [
    'delivery_date' => 'date',
    'created_at' => 'datetime',
    'updated_at' => 'datetime'
];
}
