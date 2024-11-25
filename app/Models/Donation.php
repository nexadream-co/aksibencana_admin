<?php

namespace App\Models;

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
}
