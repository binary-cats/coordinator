<?php

namespace BinaryCats\Coordinator\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\Period\Period;

interface Booking
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function model(): MorphTo;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function resource(): MorphTo;

    /**
     * @return \Spatie\Period\Period
     */
    public function asPeriod(): Period;
}
