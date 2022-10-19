<?php

namespace App\Models;

use App\Casts\FullNameCast;
use App\Casts\BusinessAddressCast;
use App\Casts\JSONCast;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use Uuid;
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'email',
		'first_name',
		'last_name',

		'group',

		'business_name',
		'business_number',
		'business_phone_code',
		'business_phone_number',
		'business_email',

		'contact_first_name',
		'contact_last_name',
		'contact_phone_code',
		'contact_phone_number',
    ];

	protected $hidden = [
		'business_address_line_one',
		'business_address_line_two',
		'business_address_line_three',
	];

	protected $casts = [
		'fullname' => FullNameCast::class,
		'tags' => JSONCast::class,
		'business_address' => BusinessAddressCast::class
	];

    public function scopeEmail($query, $email)
    {
        return $query->where('email', $email);
    }
}
