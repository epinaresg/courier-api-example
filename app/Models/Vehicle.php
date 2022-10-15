<?php

namespace App\Models;

use App\Filters\Vehicle\VehicleFilter;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Builder;
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

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function scopeAccount($query, string $accountId)
    {
        return $query->where('account_id', $accountId);
    }

    public function scopeFilter(Builder $builder, $request)
    {
        return (new VehicleFilter($request))->filter($builder);
    }
}
