<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    use HasFactory;

    protected $fillable = [
      'bidder_id',
      'device_id',
      'bid_amount',
      'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'bidder_id', 'id');
    }

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
