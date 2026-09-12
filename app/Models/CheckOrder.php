<?php

namespace App\Models;

use App\Traits\MultiTenantModelTrait;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class CheckOrder extends Model implements HasMedia
{
    use SoftDeletes,  InteractsWithMedia, HasFactory;

    public $table = 'check_orders';

    protected $appends = ['attachment'];

    protected $dates = ['placed_at', 'created_at', 'updated_at', 'deleted_at'];

    public const ORDER_STATUS_SELECT = [
        'Processing' => 'Processing',
        'Shipped'    => 'Shipped',
        'Delivered'  => 'Delivered',
        'Canceled'   => 'Canceled',
    ];

    protected $fillable = [
        'select_user_id',
        'order_number',
        'total_amount',
        'payment_method',
        'payment_status',
        'shipping_address',
        'billing_address',
        'placed_at',
        'order_status',
        'created_by_id',
        'transaction_id',
        'products',
        'confirm_qty',
        'select_customer_id',
        'carrier_id',
        'notes',
       
       
       
    ];

    /**
     * Date serialization format
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    /**
     * Media conversions
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    /**
     * Order cancellations relationship
     */
    public function orderNumberCancellations()
    {
        return $this->hasMany(Cancellation::class, 'order_number_id', 'id');
    }

    /**
     * Order refunds relationship
     */
    public function orderRefunds()
    {
        return $this->hasMany(Refund::class, 'order_id', 'id');
    }

    /**
     * Order belongs to a user
     */
    public function select_user()
    {
        return $this->belongsTo(User::class, 'select_user_id');
    }

    /**
     * Many-to-Many relationship with products
     */
    public function select_products()
    {
        return $this->belongsToMany(Product::class, 'check_order_product', 'check_order_id', 'product_id')->withPivot('quantity');
    }

    /**
     * Get formatted placed_at date
     */
    public function getPlacedAtAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('d-m-Y H:i:s') : null;
    }

    /**
     * Set placed_at date format
     */
    public function setPlacedAtAttribute($value)
    {
        $this->attributes['placed_at'] = $value
            ? Carbon::parse($value)->format('Y-m-d H:i:s')
            : null;
    }

    /**
     * Get media attachments
     */
    public function getAttachmentAttribute()
    {
        return $this->getMedia('attachment')->map->getUrl();
    }

    /**
     * Order created by user
     */
    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    /**
     * Wallet request relationship
     */
    public function wallet()
    {
        return $this->belongsTo(WalletRequest::class, 'wallet_id');
    }
    public function cancellations()
    {
        return $this->hasMany(Cancellation::class, 'order_number_id');
    }
    
    public function products()
    {
        return $this->belongsToMany(Product::class, 'check_order_product')
                    ->withPivot('quantity');
    }
    
    public function companies()
    {
        return $this->belongsTo(AddCompany::class, 'company_id'); // Agar yeh column hai
    }
    public function checkOrder()
    {
    return $this->belongsTo(CheckOrder::class, 'check_order_id');
    }
    public function product()
    {
    return $this->belongsToMany(Product::class, 'check_order_product', 'check_order_id', 'product_id')->withPivot('quantity');
    }
    public function select_customer()
    {
        return $this->belongsTo(Customer::class, 'select_customer_id');
    }
    public function carrier()
{
    return $this->belongsTo(Carrier::class, 'carrier_id'); // Assuming foreign key is 'carrier_id'
}


    

}
