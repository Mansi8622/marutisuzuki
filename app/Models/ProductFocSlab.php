<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductFocSlab extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'slab_name',
        'buy_qty',
        'free_qty',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}