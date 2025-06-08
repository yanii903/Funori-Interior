<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_code',
        'customer_name',
        'customer_email',
        'customer_phone',
        'shipping_address',
        'subtotal_amount',
        'shipping_fee',
        'discount_amount',
        'tax_amount',
        'total_amount',
        'payment_method_id',
        'payment_status',
        'shipping_method_id',
        'order_status',
        'customer_note',
        'admin_note',
        'ordered_at',
        'delivered_at',
        'cancelled_at',
        'cancellation_reason',
    ];

    protected $casts = [
        'subtotal_amount' => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'ordered_at' => 'datetime',
        'delivered_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'payment_status' => 'string', // Enum
        'order_status' => 'string', // Enum
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function shippingMethod()
    {
        return $this->belongsTo(ShippingMethod::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function promotions()
    {
        return $this->belongsToMany(Promotion::class, 'order_promotion')->withPivot('discount_applied');
    }

    public static function getAllowedStatusTransitions(): array
    {
        return [
            'pending_confirmation' => ['processing', 'cancelled'],
            'processing'           => ['shipped', 'cancelled'],
            'shipped'              => ['delivered', 'cancelled'],
            'delivered'            => ['returned'],
            'returned'             => [],
            'cancelled'            => [],
        ];
    }
}