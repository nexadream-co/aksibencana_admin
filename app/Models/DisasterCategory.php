<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DisasterCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
}
