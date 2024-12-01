<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Delivery extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function branchOffice()
    {
        return $this->belongsTo(BranchOffice::class, 'branch_office_id');
    }

    public function courier()
    {
        return $this->belongsTo(User::class, 'delivered_by');
    }

    public function disaster()
    {
        return $this->belongsTo(Disaster::class, 'disaster_id');
    }

    public function station()
    {
        return $this->belongsTo(DisasterStation::class, 'disaster_station_id');
    }

    public function logistics()
    {
        return $this->hasMany(Logistic::class, 'delivery_id');
    }
}
