<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Logistic extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function disaster()
    {
        return $this->belongsTo(Disaster::class, 'disaster_id');
    }

    public function branchOffice()
    {
        return $this->belongsTo(BranchOffice::class, 'branch_office_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function goods()
    {
        return $this->belongsTo(Good::class, 'good_id');
    }

    public function expedition()
    {
        return $this->belongsTo(Expedition::class, 'expedition_id');
    }
    
    public function delivery()
    {
        return $this->belongsTo(Delivery::class, 'delivery_id');
    }
}
