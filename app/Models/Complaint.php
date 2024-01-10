<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'employee_id',
        'name',
        'email',
        'phone',
        'subject',
        'details',
        'status'
    ];
    public function complaint_replies()
    {
        return $this->hasOne(ComplaintReply::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
