<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Verification extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'name',
        'email',
        'number',
        'image',
        'aadhar_image',
        'pan_image',
        'reseller_code',
        'verification_status',
    ];

    // Relation to Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
