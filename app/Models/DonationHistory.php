<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DonationHistory extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function donation()
    {
        return $this->belongsTo(Donation::class, 'donation_id');
    }

    public function prayer()
    {
        return $this->hasOne(DonationPrayer::class, 'donation_history_id');
    }
}
