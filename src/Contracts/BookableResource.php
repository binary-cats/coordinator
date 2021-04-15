<?php

namespace BinaryCats\Coordinator\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface BookableResource
{
    /**
     * This model can be a part of many bookings.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function bookings(): MorphMany;

    /**
     * @param  \BinaryCats\Coordinator\Contracts\CanBookResources $model
     * @param  iterable                                           $attributes
     * @return \BinaryCats\Coordinator\Contracts\Booking
     */
    public function createBookingFor(CanBookResources $model, iterable $attributes = []): Booking;

    /**
     * @param  \BinaryCats\Coordinator\Contracts\CanBookResources $model
     * @param  iterable                                           $attributes
     * @return \BinaryCats\Coordinator\Contracts\Booking
     */
    public function makeBookingFor(CanBookResources $model, iterable $attributes = []): Booking;
}
