<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use Uuid;
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'shipment_id',
        'type',
        'date',
        'package_content',
        'package_instruction',
        'address',
        'address_reference',
        'delivery_zone_id',
        'contact_full_name',
        'contact_phone_code',
        'contact_phone_number',
        'contact_email',
        'payment_method_id',
        'total_receivable',

        'order',
        'state_id',
    ];

    protected $dates = ['date'];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function deliveryZone()
    {
        return $this->belongsTo(DeliveryZone::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function scopeAccount($query, string $accountId)
    {
        return $query->where('account_id', $accountId);
    }
}
