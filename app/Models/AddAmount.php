<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddAmount extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'description',
        'status',
    ];

    // Relationship
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Status constants
    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVE = 'approve';
    public const STATUS_REJECT = 'reject';

    public const STATUS_SELECT = [
        self::STATUS_PENDING => 'Pending',
        self::STATUS_APPROVE => 'Approved',
        self::STATUS_REJECT => 'Rejected',
    ];
}
