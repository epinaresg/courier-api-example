<?php

namespace App\Models;

use App\Filters\Driver\DriverFilter;
use App\Traits\Uuid;
use Illuminate\Contracts\Database\Eloquent\Builder;
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

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function scopeAccount($query, string $accountId)
    {
        return $query->where('account_id', $accountId);
    }

    public function scopeFilter(Builder $builder, $request)
    {
        return (new DriverFilter($request))->filter($builder);
    }
}
