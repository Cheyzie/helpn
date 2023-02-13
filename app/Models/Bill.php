<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bill extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'details',
        'contacts',
        'user_id',
        'city_id',
        'type_id'
    ];

    protected $with = [
        'user:id,name',
        'city',
        'type',
    ];

    protected $hidden = [
        'contacts',
        'type_id',
        'city_id',
        'user_id',
        'updated_at',
    ];

    /**
     * Bill`s city
     *
     * @return BelongsTo
     */
    public function city(): BelongsTo {
        return $this->belongsTo(City::class);
    }

    /**
     * Type to which the bill belongs
     *
     * @return BelongsTo
     */
    public function type(): BelongsTo {
        return $this->belongsTo(Type::class);
    }

    /**
     * Bill`s author
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
}
