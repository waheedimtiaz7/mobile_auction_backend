<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use phpseclib3\Crypt\DES;

class Device extends Model
{
    use HasFactory;

    protected $fillable =[
        'device_name',
        'model',
        'picture',
        'os',
        'ui',
        'dimensions',
        'weight',
        'color',
        'sim',
        'cpu',
        'gpu',
        'size',
        'resolution',
        'ram',
        'rom',
        'sdcard',
        'bluetooth',
        'wifi',
        'battery',
        'price',
        'suggest_price',
        'bidder_id',
        'user_id',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function bids()
    {
        return $this->hasMany(Bid::class);
    }
    public function bidder()
    {
        return $this->belongsTo(User::class, 'bidder_id', 'id');
    }

    public function highestBid()
    {
        return $this->hasOne(Bid::class)->orderByDesc('bid_amount')->limit(1);
    }

    public function latestBid(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Bid::class)->latestOfMany();
    }

    public function acceptedBid(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Bid::class)->whereStatus('Accepted');
    }

    public function deviceImages()
    {
        return $this->hasMany(DeviceImage::class);
    }
}
