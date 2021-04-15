<?php

namespace BinaryCats\Coordinator;

use Spatie\Period\Boundaries;
use Spatie\Period\Precision;

class Coordinator
{
    /**
     * @return string
     */
    public static function bookingModel(): string
    {
        return config('coordinator.booking_model');
    }

    /**
     * @return \Spatie\Period\Boundaries
     */
    public static function defaultBoundaries(): Boundaries
    {
        return config('coordinator.boundaries');
    }

    /**
     * @return \Spatie\Period\Precision
     */
    public static function defaultPrecision(): Precision
    {
        return config('coordinator.precision');
    }
}
