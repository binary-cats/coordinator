<?php

namespace BinaryCats\Coordinator\Eloquent\Concerns;

use BinaryCats\Coordinator\Eloquent\Factories\BookingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory as BaseHasFactory;

trait HasFactory
{
    use BaseHasFactory;

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return new BookingFactory;
    }
}
