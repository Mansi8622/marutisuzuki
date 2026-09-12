<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Godown extends Model
{
    protected $fillable = [
        'name', 'location', 'capacity', 'status'
    ];

    public function products()
    {
    return $this->hasMany(Product::class, 'godown_id');
    }


}
