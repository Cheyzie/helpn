<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;

    protected $with = [
        'city:id,name',
    ];

    protected $hidden = [
        'contacts',
        'type_id',
        'city_id',
        'user_id',
        'updated_at',
    ];

    public function city() {
        return $this->belongsTo(City::class);
    }

    public function type() {
        return $this->belongsTo(Type::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
