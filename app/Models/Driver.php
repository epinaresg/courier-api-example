<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Driver extends Model
{
    use Uuid;
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [


        'first_name',
        'last_name',
        'email',

        'identification_number',
        'licence_number',

        'phone_code',
        'phone_number',

        'vehicle_id',
        'vehicle_brand',
        'vehicle_model',
        'vehicle_identification_number'
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }


}
