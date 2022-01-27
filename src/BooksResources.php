<?php

namespace BinaryCats\Coordinator;

use BinaryCats\Coordinator\Contracts\BookableResource;
use BinaryCats\Coordinator\Contracts\Booking;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @mixin Model
 */
trait BooksResources
{
    /**
     * Boot the BooksResources trait for the model.
     *
     * @return void
     */
    public static function bootBooksResources()
    {
        static::deleted(fn (Model $model) => $model->bookings()->delete());
    }

    /**
     * This model has many bookings.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function bookings(): MorphMany
    {
        return $this->morphMany(Coordinator::bookingModel(), 'model');
    }

    /**
     * Create new Booking.
     *
     * @param  \BinaryCats\Coordinator\Contracts\BookableResource  $resource
     * @param  array  $attributes
     * @return \BinaryCats\Coordinator\Contracts\Booking
     */
    public function createBookingFor(BookableResource $resource, $attributes = []): Booking
    {
        return tap($this->makeBookingFor($resource, $attributes), fn ($model) => $model->save());
    }

    /**
     * Make a new Booking without saving it.
     *
     * @param  \BinaryCats\Coordinator\Contracts\BookableResource  $resource
     * @param  array  $attributes
     * @return \BinaryCats\Coordinator\Contracts\Booking
     */
    public function makeBookingFor(BookableResource $resource, $attributes = []): Booking
    {
        return $this->bookings()
            ->make($attributes)
            ->resource()
            ->associate($resource);
    }
}
