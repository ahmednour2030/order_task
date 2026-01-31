<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'total_amount',
        'status',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'status' => OrderStatus::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function successfulPayment()
    {
        return $this->hasOne(Payment::class)
            ->where('status', 'successful');
    }

    public function isPending(): bool
    {
        return $this->status === OrderStatus::PENDING;
    }
}
