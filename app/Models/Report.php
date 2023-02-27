<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $with = ['user:id,name'];

    protected $fillable = [
        'details',
        'bill_id',
        'user_id',
    ];


    public function bill() {
        return $this->belongsTo(Bill::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
