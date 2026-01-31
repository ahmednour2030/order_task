<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'order_id',
        'payment_id',
        'amount',
        'status',
        'method'
    ];

    // علاقة مع الـ Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
