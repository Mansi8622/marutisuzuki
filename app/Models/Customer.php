<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Customer extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'phone', 'password','profile_photo'
    ];

    protected $hidden = [
        'password', 'remember_token'
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    public function country()
{
    return $this->belongsTo(Country::class);
}

public function state()
{
    return $this->belongsTo(State::class);
}

/*************  ✨ Windsurf Command ⭐  *************/
    /**
     * Get the city associated with the customer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

/*******  9a5c8a0c-3504-4fec-ab74-1316e69d67d1  *******/
public function city()
{
    return $this->belongsTo(City::class);
}

/*************  ✨ Windsurf Command ⭐  *************/
    /**
     * Get the address associated with the customer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */

/*******  8c9630d9-4331-400d-afe7-7650e6c52bbc  *******/
public function address()
{
    return $this->hasOne(Address::class);  // One address per customer
}


}