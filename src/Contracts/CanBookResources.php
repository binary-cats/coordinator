<?php

namespace BinaryCats\Coordinator\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface CanBookResources
{
    /**
     * This model can be a part of many bookings.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function bookings(): MorphMany;

    /**
     * @param  \BinaryCats\Coordinator\Contracts\BookableResource $resource
     * @param  iterable                                           $attributes
     * @return \BinaryCats\Coordinator\Contracts\Booking
     */
    public function createBookingFor(BookableResource $resource, iterable $attributes = []): Booking;

    /**
     * @param  \BinaryCats\Coordinator\Contracts\BookableResource $resource
     * @param  iterable                                           $attributes
     * @return \BinaryCats\Coordinator\Contracts\Booking
     */
    public function makeBookingFor(BookableResource $resource, iterable $attributes = []): Booking;
}
