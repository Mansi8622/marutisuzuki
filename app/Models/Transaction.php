<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'order_id',
        'transaction_id',
        'request_amount',
        'vendor_id',
        'status',
        'transaction_type',
        'paid_amount',
        'total_amount',
        'created_by_id',
    ];

    // Relationship to the vendor (user)
    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    // Relationship to the creator (admin or another user)
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    // Relationship to the order (optional, but useful)
    public function order()
    {
        return $this->belongsTo(CheckOrder::class, 'order_id');
    }
}
