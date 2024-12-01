<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Disaster extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, "created_by");
    }

    public function category()
    {
        return $this->belongsTo(DisasterCategory::class, "disaster_category_id");
    }

    public function district()
    {
        return $this->belongsTo(District::class, "district_id");
    }

    public function deliveries()
    {
        return $this->hasMany(Delivery::class, 'disaster_id');
    }
}
