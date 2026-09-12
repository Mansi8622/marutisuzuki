<?php

namespace App\Models;

use App\Notifications\VerifyUserNotification;
use Carbon\Carbon;
use DateTimeInterface;
use Hash;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

use App\Models\Role;

use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements HasMedia
{
    use HasApiTokens, SoftDeletes, Notifiable, InteractsWithMedia, HasFactory;

    public $table = 'users';

    protected $hidden = [
        'remember_token',
        'password',
    ];

    public const STATUS_SELECT = [
        'active'   => 'Active',
        'inactive' => 'Inactive',
    ];

    protected $dates = [
        'email_verified_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $appends = [
        'kyc_documents_front',
        'kyc_documents_back',
        'business_registration_certificate',
    ];

    public const BUSINESS_TYPE_SELECT = [
        'Retailer'     => 'Retailer',
        'Wholesaler'   => 'Wholesaler',
        'Manufacturer' => 'Manufacturer',
        'Sells Man'    => 'Sells',

    ];

    protected $fillable = [
        'name',
        'email',
        'phone',
        'remember_token',
        'approved',
        'business_name',
        'business_type',
        'gst_number',
        'pan_number',
        'business_address',
        'bank_name',
        'account_number',
        'ifsc_code',
        'account_holder_name',
        'license_details',
        'status',
        'vendor',
        'password',
        'email_verified_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function getIsAdminAttribute()
    {
        return $this->roles()->where('id', 1)->exists();
    }

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        self::created(function (self $user) {
            $registrationRole = config('panel.registration_default_role');
            if (! $user->roles()->get()->contains($registrationRole)) {
                $user->roles()->attach($registrationRole);
            }
        });
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    public function selectUserStockTransfers()
    {
        return $this->hasMany(StockTransfer::class, 'select_user_id', 'id');
    }

    public function selectUserCheckOrders()
    {
        return $this->hasMany(CheckOrder::class, 'select_user_id', 'id');
    }

    public function customerDisputes()
    {
        return $this->hasMany(Dispute::class, 'customer_id', 'id');
    }

    public function vendorWalletRequests()
    {
        return $this->hasMany(WalletRequest::class, 'vendor_id', 'id');
    }

    public function userUserAlerts()
    {
        return $this->belongsToMany(UserAlert::class);
    }

    public function getKycDocumentsFrontAttribute()
    {
        $files = $this->getMedia('kyc_documents_front');
        $files->each(function ($item) {
            $item->url       = $item->getUrl();
            $item->thumbnail = $item->getUrl('thumb');
            $item->preview   = $item->getUrl('preview');
        });

        return $files;
    }

    public function getKycDocumentsBackAttribute()
    {
        $file = $this->getMedia('kyc_documents_back')->last();
        if ($file) {
            $file->url       = $file->getUrl();
            $file->thumbnail = $file->getUrl('thumb');
            $file->preview   = $file->getUrl('preview');
        }

        return $file;
    }

    public function getBusinessRegistrationCertificateAttribute()
    {
        $files = $this->getMedia('business_registration_certificate');
        $files->each(function ($item) {
            $item->url       = $item->getUrl();
            $item->thumbnail = $item->getUrl('thumb');
            $item->preview   = $item->getUrl('preview');
        });

        return $files;
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function setPasswordAttribute($input)
    {
        if ($input) {
            $this->attributes['password'] = app('hash')->needsRehash($input) ? Hash::make($input) : $input;
        }
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    public function getEmailVerifiedAtAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setEmailVerifiedAtAttribute($value)
    {
        $this->attributes['email_verified_at'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    // User Model
public function address()
{
    return $this->hasOne(Address::class); // Make sure the relationship is correctly defined
}

public function assignedRetailers()
{
    return $this->hasMany(AssignSalesman::class);
}



public function expenses()
{
    return $this->hasMany(Expense::class, 'user_id');
}


public function addAmounts()
{
    return $this->hasMany(AddAmount::class);
}



}



