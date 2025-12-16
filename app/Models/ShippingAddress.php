<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingAddress extends Model
{
    protected $fillable = [
        'user_id',
        'full_name',
        'phone_number',
        'province',
        'district',
        'ward',
        'address',
        'lat',
        'lng',
        'default'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
