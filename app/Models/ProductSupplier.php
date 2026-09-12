<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSupplier extends Model
{
    use HasFactory;

    protected $table = 'product_supplier';

    protected $fillable = [
        'supplier_id', 'product_id', 'quantity', 'price', 'discount', 'price_1', 'price_2', 'price_3','bill_number','purchasing_date','purchasing_amount',
        'bill_amount','company_id','billing_id','products'
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}