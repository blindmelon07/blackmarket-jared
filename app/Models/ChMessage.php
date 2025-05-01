<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Chatify\Traits\UUID;

class ChMessage extends Model
{
    use UUID;

    protected $fillable = [
        'from_id',
        'to_id',
        'body',
        'attachment',
        'seen',
        'offer_price',
        'offer_status',
        'parent_message_id'
    ];

    protected $casts = [
        'seen' => 'boolean',
        'offer_price' => 'decimal:2'
    ];

    public function parentMessage()
    {
        return $this->belongsTo(ChMessage::class, 'parent_message_id');
    }

    public function childMessages()
    {
        return $this->hasMany(ChMessage::class, 'parent_message_id');
    }

    public function isNegotiationMessage()
    {
        return !is_null($this->offer_price);
    }

    public function getOfferStatusLabelAttribute()
    {
        return match($this->offer_status) {
            'pending' => 'Pending',
            'accepted' => 'Accepted',
            'rejected' => 'Rejected',
            'counter_offered' => 'Counter Offered',
            default => 'Unknown'
        };
    }
}
