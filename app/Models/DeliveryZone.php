<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeliveryZone extends Model
{
    use Uuid;
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [


        'name',
        'type',
        'price_pick_up',
        'price_drop_off',
        'currency',
    ];


}
