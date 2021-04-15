<?php

namespace BinaryCats\Coordinator\Eloquent\Concerns;

use BinaryCats\Coordinator\Coordinator;
use Carbon\Carbon;
use Spatie\Period\Period;

trait Periodable
{
    /**
     * @return \Spatie\Period\Period
     */
    public function asPeriod(): Period
    {
        return Period::make(
            $this->starts_at,
            $this->ends_at,
            Coordinator::defaultPrecision(),
            Coordinator::defaultBoundaries()
        );
    }

    /**
     * True if the booking period starts after the argument (or now).
     *
     * @param  \Carbon\Carbon|null $at
     * @return bool
     */
    public function isFuture($at = null): bool
    {
        $at = empty($at) ? Carbon::now() : Carbon::parse($at);

        return $this->asPeriod()->startsAfter($at);
    }

    /**
     * True if the booking period ends after the argument (or now).
     *
     * @param  \Carbon\Carbon|null $at
     * @return bool
     */
    public function isPast($at = null): bool
    {
        $at = empty($at) ? Carbon::now() : Carbon::parse($at);

        return $this->asPeriod()->endsBefore($at);
    }

    /**
     * True if the booking period contains the argument (or now).
     *
     * @param  \Carbon\Carbon|\Spatie\Period\Period|string|null $at
     * @return bool
     */
    public function isCurrent($at = null): bool
    {
        if (empty($at)) {
            return $this->asPeriod()->contains(Carbon::now());
        }

        if ($at instanceof Period) {
            return $this->overlapsWith($at);
        }

        return $this->asPeriod()->contains(Carbon::parse($at));
    }

    /**
     * Shortcut to Period's overlaps with.
     *
     * @param  \Spatie\Period\Period $period
     * @return bool
     */
    public function overlapsWith(Period $period)
    {
        return $this->asPeriod()->overlapsWith($period);
    }
}
