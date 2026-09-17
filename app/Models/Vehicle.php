<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'subcategory_id'];

    public function subcategory()
    {
        return $this->belongsTo(ProductCategory::class, 'subcategory_id');
    }
}
