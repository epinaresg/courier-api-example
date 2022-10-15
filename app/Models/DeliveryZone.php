<?php

namespace App\Models;

use App\Filters\DeliveryZone\DeliveryZoneFilter;
use App\Traits\Uuid;
use Illuminate\Contracts\Database\Eloquent\Builder;
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
        return (new DeliveryZoneFilter($request))->filter($builder);
    }
}
