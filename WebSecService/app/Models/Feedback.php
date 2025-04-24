<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Feedback extends Model
{
    use HasFactory;

    // Explicitly define the table name to match the migration
    protected $table = 'feedbacks';

    protected $fillable = [
        'order_id', 
        'user_id', 
        'reason', 
        'comments', 
        'resolved', 
        'admin_response',
        'resolved_by',
        'resolved_at'
    ];

    protected $casts = [
        'resolved' => 'boolean',
        'resolved_at' => 'datetime',
    ];

    // Define available feedback reasons
    public static function getReasons(): array
    {
        return [
            'changed_mind' => 'Changed my mind',
            'found_better_price' => 'Found a better price elsewhere',
            'delivery_time' => 'Delivery time is too long',
            'payment_issue' => 'Issue with payment method',
            'ordered_by_mistake' => 'Ordered by mistake',
            'other' => 'Other reason'
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function resolvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }
}
