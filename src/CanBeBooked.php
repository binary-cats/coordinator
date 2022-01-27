<?php

namespace BinaryCats\Coordinator;

use BinaryCats\Coordinator\Contracts\Booking;
use BinaryCats\Coordinator\Contracts\CanBookResources;
use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @mixin Model
 */
trait CanBeBooked
{
    /**
     * Boot the BooksResources trait for the model.
     *
     * @return void
     */
    public static function bootCanBeBooked()
    {
        static::deleted(fn (Model $model) => $model->bookings()->delete());
    }

    /**
     * This model can be a part of many bookings.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function bookings(): MorphMany
    {
        return $this->morphMany(Coordinator::bookingModel(), 'resource');
    }

    /**
     * True if the resource is available at a given argument.
     *
     * @param  string|\DateTimeInterface|\Carbon\Carbon|\Spatie\Period\Period  $at
     * @param  bool  $includeCanceled
     * @return bool
     */
    public function isAvailableAt($at, $includeCanceled = false): bool
    {
        return $this->bookings
            ->filter(fn (Booking $booking) => $booking->isCurrent($at))
            ->reject(fn (Booking $booking) => $includeCanceled ? false : $booking->is_canceled)
            ->isEmpty();
    }

    /**
     * True if the resource is not available at a given argument.
     *
     * @param  string|\DateTimeInterface|\Carbon\Carbon|\Spatie\Period\Period  $at
     * @param  bool  $includeCanceled
     * @return bool
     */
    public function isBookedAt($at, $includeCanceled = false): bool
    {
        return ! $this->isAvailableAt($at, $includeCanceled);
    }

    /**
     * True if the resource is available at a given argument.
     *
     * @param  \BinaryCats\Coordinator\Contracts\CanBookResources  $model
     * @param  \Closure  $closure
     * @return bool
     */
    public function isAvailableFor(CanBookResources $model, Closure $closure): bool
    {
        return $closure($model, $this);
    }

    /**
     * Create new Booking.
     *
     * @param  \BinaryCats\Coordinator\Contracts\CanBookResources  $model
     * @param  array  $attributes
     * @return \BinaryCats\Coordinator\Contracts\Booking
     */
    public function createBookingFor(CanBookResources $model, $attributes = []): Booking
    {
        return tap($this->makeBookingFor($model, $attributes), fn ($model) => $model->save());
    }

    /**
     * Make a new Booking without saving it.
     *
     * @param  \BinaryCats\Coordinator\Contracts\CanBookResources  $model
     * @param  array  $attributes
     * @return \BinaryCats\Coordinator\Contracts\Booking
     */
    public function makeBookingFor(CanBookResources $model, $attributes = []): Booking
    {
        return $this->bookings()
            ->make($attributes)
            ->model()
            ->associate($model);
    }
}
