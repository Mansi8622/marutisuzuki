<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class AssignSalesman extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'number',
        'image',
    ];

    // Relation to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Register the media collections
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('profile_image')->singleFile(); // This handles the profile_image collection
    }

    // Get the first media URL
    public function getProfileImageUrl()
    {
        return $this->getFirstMediaUrl('profile_image');
    }
}

