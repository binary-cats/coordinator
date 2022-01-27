<?php

namespace BinaryCats\Coordinator\Eloquent;

use BinaryCats\Coordinator\Contracts\Booking as Contract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * BinaryCats\Coordinator\Eloquent\Booking.
 *
 * @property \Carbon\Carbon|string $starts_at
 * @property \Carbon\Carbon|string $ends_at
 * @property \Carbon\Carbon|string $canceled_at
 */
class Booking extends Model implements Contract
{
    use SoftDeletes;
    use Concerns\Attributes;
    use Concerns\HasFactory;
    use Concerns\Periodable;
    use Concerns\Scopes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'starts_at',
        'ends_at',
        'canceled_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var string[]
     */
    protected $casts = [
        'canceled_at' => 'datetime',
        'ends_at' => 'datetime',
        'starts_at' => 'datetime',
    ];

    /**
     * Model that owns the booking of a resource.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Model that owns the booking of a resource.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function resource(): MorphTo
    {
        return $this->morphTo();
    }
}
