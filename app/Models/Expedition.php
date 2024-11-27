<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expedition extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }
}
