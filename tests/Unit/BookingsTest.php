<?php

namespace Tests\Unit;

use BinaryCats\Coordinator\Contracts\Booking;
use Tests\TestCase;
use Spatie\Period\Period;

class BookingsTest extends TestCase
{
    /** @test */
    public function booking_can_be_made(): void
    {
        $booking = $this->makeNewBooking();

        $this->assertInstanceOf(Booking::class, $booking);
    }

    /** @test */
    public function booking_can_be_expressed_as_period(): void
    {
        $period = $this->makeNewBooking()->factory()->make()->asPeriod();

        $this->assertInstanceOf(Period::class, $period);
    }

    /** @test */
    public function booking_can_be_cancelled(): void
    {
        $booking = $this->makeNewBooking()->factory()->canceled()->make();

        $this->assertTrue($booking->is_canceled);
    }

    /** @test */
    public function booking_is_not_cancelled_without_date(): void
    {
        $booking = $this->makeNewBooking()->factory()->make();

        $this->assertFalse($booking->is_canceled);
    }

    /** @test */
    public function booking_can_be_current(): void
    {
        $booking = $this->makeNewBooking()->factory()->make([
            'starts_at' => today(),
            'ends_at' => today()->addDay(),
        ]);

        $this->assertTrue($booking->isCurrent());
        $this->assertTrue($booking->is_current);
    }

    /** @test */
    public function booking_can_be_past(): void
    {
        $booking = $this->makeNewBooking()->factory()->make([
            'starts_at' => today()->subDay(),
            'ends_at' => today(),
        ]);

        $this->assertTrue($booking->isPast());
        $this->assertTrue($booking->is_past);
    }

    /** @test */
    public function booking_can_be_future(): void
    {
        $booking = $this->makeNewBooking()->factory()->make([
            'starts_at' => today()->addDay(),
            'ends_at' => today()->addDay()->endOfDay(),
        ]);

        $this->assertTrue($booking->isFuture());
        $this->assertTrue($booking->is_future);
    }

    /**
     * @return \BinaryCats\Coordinator\Eloquent\Booking
     */
    protected function makeNewBooking()
    {
        return resolve(config('coordinator.booking_model'));
    }
}
