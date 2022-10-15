<?php

namespace App\Models;

use App\Filters\PaymentMethod\PaymentMethodFilter;
use App\Traits\Uuid;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentMethod extends Model
{
    use Uuid;
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [


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
        return (new PaymentMethodFilter($request))->filter($builder);
    }
}
