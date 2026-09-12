<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Replacement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'customer_id',
        'product_id',
        'check_order_id',
        'order_number',
        'wallet_request_id',
        'transaction_id',
        'company_id',
        'issues',
        'notes',
        'attachment',
        'status',
        'info',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_notes',
        
        
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function products()
    {
        return $this->belongsTo(Product::class,'product_id');
    }

    public function checkOrder()
    {
        return $this->belongsTo(CheckOrder::class);
    }
    public function checkOrders()
    {
        return $this->belongsTo(CheckOrder::class,'check_order_id');
    }
    public function walletRequest()
    {
        return $this->belongsTo(WalletRequest::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function company()
    {
        return $this->belongsTo(AddCompany::class);
    }
}
