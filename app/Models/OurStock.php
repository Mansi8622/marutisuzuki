<?php

namespace App\Models;

use App\Traits\MultiTenantModelTrait;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OurStock extends Model
{
    use SoftDeletes, MultiTenantModelTrait, HasFactory;

    public $table = 'our_stocks';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'select_product_id',
        'quantity_available',
        'sku',
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by_id',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function selectProductStockTransfers()
    {
        return $this->belongsToMany(StockTransfer::class);
    }

    public function select_product()
    {
        return $this->belongsTo(Product::class, 'select_product_id');
    }

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }
}
