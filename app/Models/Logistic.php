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
}
