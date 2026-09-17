<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductFitment extends Model
{
    public $timestamps = false;
    protected $fillable = ['category_id', 'vehicle_id'];
    public function category() { return $this->belongsTo(ProductCategory::class, 'category_id'); }
    public function vehicle() { return $this->belongsTo(Vehicle::class); }

    public function snapshot(): array
    {
        return ['fitment_id' => $this->id, 'category_id' => $this->category_id,
            'subcategory_id' => $this->vehicle->subcategory_id, 'vehicle_id' => $this->vehicle_id,
            'category_name' => $this->category->name, 'subcategory_name' => $this->vehicle->subcategory->name,
            'vehicle_name' => $this->vehicle->name];
    }
}
