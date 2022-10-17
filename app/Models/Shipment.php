<?php

namespace App\Models;

use App\Filters\Shipment\ShipmentFilter;
use App\Traits\Uuid;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shipment extends Model
{
    use Uuid;
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'customer_id',
        'vehicle_id',
        'tasks_qty',
        'tasks_missing_qty',

        'total_receivable',
        'total_pick_up',
        'total_drop_off',
        'total'
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function scopeAccount($query, string $accountId)
    {
        return $query->where('account_id', $accountId);
    }

    public function scopeFilter(Builder $builder, $request)
    {
        return (new ShipmentFilter($request))->filter($builder);
    }
}
