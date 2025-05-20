<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class SellerApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'business_name', 'phone', 'status', 'admin_note'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}