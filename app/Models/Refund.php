<?php

namespace App\Models;

use App\Traits\MultiTenantModelTrait;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Refund extends Model implements HasMedia
{
    use SoftDeletes, MultiTenantModelTrait, InteractsWithMedia, HasFactory;

    public $table = 'refunds';

    protected $appends = [
        'attachment',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'product_id',
        'company_id',
        'order_id',
        'status',
        'note',
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by_id',
    ];

    public const STATUS_SELECT = [
        'Panding'    => 'Panding',
        'Processing' => 'Processing',
        'Approved'   => 'Approved',
        'Rejected'   => 'Rejected',
        'Closed'     => 'Closed',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function company()
    {
        return $this->belongsTo(AddCompany::class, 'company_id');
    }

    public function order()
    {
        return $this->belongsTo(CheckOrder::class, 'order_id');
    }

    public function getAttachmentAttribute()
    {
        return $this->getMedia('attachment');
    }

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

}
