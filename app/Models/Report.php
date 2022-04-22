<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $with = [
        'bill',
    ];

    protected $hidden = [
        'user_id',
        'bill_id',
    ];

    public function bill() {
        $this->belongsTo(Bill::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
