<?php

namespace App\Models;

use App\Traits\MultiTenantModelTrait;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cancellation extends Model
{
    use SoftDeletes;

    public $table = 'cancellations';

    protected $dates = [
        'requested_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const STATUS_SELECT = [
        'Approved' => 'Approved',
        'Hold'     => 'Hold',
        'Rejected' => 'Decline',
    ];

    protected $fillable = [
        'payment',
        'requested_at',
        'status',
        'order_number_id',
        'product_id',
        'company_id',
        'created_by_id',
        'user_id',
        'customer_id',
        'problem',
        'notes',
        'order_id',
        'attachment',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function order_number()
    {
        return $this->belongsTo(CheckOrder::class, 'order_number_id'); // Check the foreign key 'order_number_id'
    }

    

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function getRequestedAtAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setRequestedAtAttribute($value)
    {
        $this->attributes['requested_at'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }
    // App\Models\Cancellation.php
    public function addCompany()
    {
    return $this->belongsTo(AddCompany::class, 'company_id'); // 'company_id' is the foreign key in the 'cancellations' table
    }

}
