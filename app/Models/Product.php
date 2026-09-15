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

class Product extends Model implements HasMedia
{
    use SoftDeletes, MultiTenantModelTrait, InteractsWithMedia, HasFactory;

    public $table = 'products';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $appends = [
        'photo',
        'product_photo_2',
        'product_photo_3',
    ];

    protected $fillable = [
        'name',
        'item_code',
        'hsn_code',
        'godown_id',
        'description',
        'price',
        'discount',
        'price_1',
        'rate_2',
        'rate_3',
        'sku',
        'quantity',
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by_id',
        'gst',
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

    public function selectProductOurStocks()
    {
        return $this->hasMany(OurStock::class, 'select_product_id', 'id');
    }

    public function selectProductCheckGodowns()
    {
        return $this->hasMany(CheckGodown::class, 'select_product_id', 'id');
    }

    public function productCancellations()
    {
        return $this->hasMany(Cancellation::class, 'product_id', 'id');
    }

    public function productRefunds()
    {
        return $this->hasMany(Refund::class, 'product_id', 'id');
    }

    public function selectProductCheckOrders()
    {
        return $this->belongsToMany(CheckOrder::class);
    }

    public function fitments()
    {
        return $this->hasMany(ProductFitment::class);
    }

    public function availableFitments()
    {
        return $this->fitments()->with(['category', 'vehicle.subcategory'])
            ->whereHas('category', fn ($q) => $q->where('is_subcategory', false)->where('has_subcategories', true))
            ->whereHas('vehicle.subcategory')->get()->filter(function ($fitment) {
                return $this->categories->contains($fitment->category_id)
                    && $fitment->category->subcategories()->whereKey($fitment->vehicle->subcategory_id)->exists();
            });
    }

    public function categories()
    {
        return $this->belongsToMany(ProductCategory::class);
    }

    public function tags()
    {
        return $this->belongsToMany(ProductTag::class);
    }

    public function select_companies()
    {
        return $this->belongsToMany(AddCompany::class)->withPivot('product_id', 'add_company_id');
    }

    public function getPhotoAttribute()
    {
        $files = $this->getMedia('photo');
        $files->each(function ($item) {
            $item->url       = $item->getUrl();
            $item->thumbnail = $item->getUrl('thumb');
            $item->preview   = $item->getUrl('preview');
        });

        return $files;
    }

    public function getProductPhoto2Attribute()
    {
        $files = $this->getMedia('product_photo_2');
        $files->each(function ($item) {
            $item->url       = $item->getUrl();
            $item->thumbnail = $item->getUrl('thumb');
            $item->preview   = $item->getUrl('preview');
        });

        return $files;
    }

    public function getProductPhoto3Attribute()
    {
        $file = $this->getMedia('product_photo_3')->last();
        if ($file) {
            $file->url       = $file->getUrl();
            $file->thumbnail = $file->getUrl('thumb');
            $file->preview   = $file->getUrl('preview');
        }

        return $file;
    }

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }
    
    public function companies()
    {
    return $this->belongsToMany(AddCompany::class, 'add_company_product', 'product_id', 'add_company_id');
    }
    public function godown()
    {
    return $this->belongsTo(Godown::class);
    }
    public function our_stock()
    {
        return $this->hasOne(OurStock::class, 'select_product_id', 'id');
    }
    public function ourStock()
{
    return $this->hasOne(OurStock::class, 'select_product_id');
}

public function our_stocks()
{
    return $this->hasMany(OurStock::class, 'select_product_id');
}

    public function sellingPrice(): float
    {
        if (auth('customer')->check()) return (float) ($this->rate_2 ?? $this->price);
        if (auth('web')->check()) return (float) ($this->price_1 ?? $this->price);
        return (float) $this->price - ((float) $this->price * (float) $this->discount / 100);
    }

    public function mrp(): float
    {
        return (float) $this->price;
    }

    public function isInStock(): bool
    {
        return (int) optional($this->ourStock)->quantity_available > 0;
    }

    


}
