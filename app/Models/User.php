<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    
    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'phone_number',
        'role_id',
        'avatar',
        'address',
        'activation_token',
        'google_id',
        'facebook_id'
    ];
    
    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    public function shippingAddresses()
    {
        return $this->hasMany(ShippingAddress::class);
    }
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function voucherUsage(){
        return $this->hasMany(VoucherUsage::class);
    }
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    //Check status
    public function isPending()
    {
        return $this->status === 'pending';
    }
    public function isActive()
    {
        return $this->status === 'active';
    }
    public function isBanned()
    {
        return $this->status === 'banned';
    }
    public function isDeleted()
    {
        return $this->status === 'deleted';
    }

}
