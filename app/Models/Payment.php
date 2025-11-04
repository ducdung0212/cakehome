<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable=[
        'order_id',
        'payment_method',
        'transaction_id',
        'amount',
        'status',
        'pay_at'
    ];
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    protected $casts = [
    'amount' => 'decimal:2',
    'pay_at' => 'datetime',
    'created_at' => 'datetime'
];
}
