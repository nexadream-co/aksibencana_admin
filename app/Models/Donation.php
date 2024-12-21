<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Donation extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function fundraiser()
    {
        return $this->belongsTo(Fundraiser::class, 'fundraiser_id');
    }

    public function category()
    {
        return $this->belongsTo(DonationCategory::class, 'donation_category_id');
    }

    public function histories()
    {
        return $this->hasMany(DonationHistory::class, 'donation_id');
    }

    public function totalDonation(): Attribute
    {
        return Attribute::get(function () {
            return $this->histories()
                ->where('status', 'paid')
                ->sum('nominal');
        });
    }

    public function totalDonator(): Attribute
    {
        return Attribute::get(function () {
            return $this->histories()
                ->where('status', 'paid')
                ->distinct('user_id')
                ->count('user_id');
        });
    }
}
