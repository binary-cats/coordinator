<?php

use BinaryCats\Coordinator\Eloquent\Booking;
use Spatie\Period\Boundaries;
use Spatie\Period\Precision;

return [

    /*
    |--------------------------------------------------------------------------
    | Coordinator: Booking Model
    |--------------------------------------------------------------------------
    |
    | The default is `BinaryCats\Coordinator\Eloquent\Booking`.
    | You are likely to extend the class or replace it with your implementation:
    | Model must implement `BinaryCats\Coordinator\Contracts\Booking`
    |
    */

    'booking_model' => Booking::class,

    /*
    |--------------------------------------------------------------------------
    | Booking Period: Precision
    |--------------------------------------------------------------------------
    | Date precision is important if you want to reliably compare two periods:
    | @see https://stitcher.io/blog/comparing-dates
    |
    | Valid options are:
    | YEAR|DAY|HOUR|MINUTE|SECOND
    */

    'precision' => Precision::SECOND(),

    /*
    |--------------------------------------------------------------------------
    | Booking Period: Boundaries
    |--------------------------------------------------------------------------
    | By default, period comparisons are done with included boundaries
    |
    | Valid options are:
    | EXCLUDE_NONE|EXCLUDE_START|EXCLUDE_END|EXCLUDE_ALL
    */

    'boundaries' => Boundaries::EXCLUDE_NONE(),
];
