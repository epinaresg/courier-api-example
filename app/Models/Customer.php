<?php

namespace App\Models;

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
    ];

    public function scopeEmail($query, $email)
    {
        return $query->where('email', $email);
    }
}
