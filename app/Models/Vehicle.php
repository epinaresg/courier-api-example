<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use Uuid;
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [

        'account_id',

        'name',
        'description',
    ];

}
