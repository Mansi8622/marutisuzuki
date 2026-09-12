<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductStockTransfer extends Model
{
    protected $table = 'product_stock_transfer';

    protected $fillable = [
        'product_id', 'from_godown_id', 'to_godown_id', 'quantity', 'transferred_at'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function fromGodown()
    {
        return $this->belongsTo(Godown::class, 'from_godown_id');
    }

    public function toGodown()
    {
        return $this->belongsTo(Godown::class, 'to_godown_id');
    }
}
