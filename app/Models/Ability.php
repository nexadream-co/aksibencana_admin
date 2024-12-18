<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ability extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function volunteers()
    {
        return $this->belongsToMany(Volunteer::class, 'volunteer_abilities');
    }
}
