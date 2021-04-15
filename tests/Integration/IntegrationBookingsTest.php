<?php

namespace BinaryCats\Coordinator\Tests\Integration;

use BinaryCats\Coordinator\Eloquent\Booking;
use BinaryCats\Coordinator\Tests\Models\BookableResourceModel;
use BinaryCats\Coordinator\Tests\Models\CanBookResourcesModel;
use BinaryCats\Coordinator\Tests\TestCase;
use Carbon\Carbon;
use DateTime;

class IntegrationBookingsTest extends TestCase
{
    /** @test */
    public function a_model_can_have_bookings(): void
    {
        $model = $this->canBookResources();

        $this->assertCount(0, Booking::get());

        $booking1 = $model->createBookingFor($this->bookableResource(), [
            'starts_at' => now(),
            'ends_at' => now()->endOfDay(),
        ]);

        $this->assertCount(1, Booking::get());
        $this->assertCount(1, $model->bookings()->get());

        $model->delete();

        $this->assertCount(0, Booking::get());
    }

    /** @test */
    public function a_resourse_can_have_bookings(): void
    {
        $resource = $this->bookableResource();

        $this->assertCount(0, Booking::get());

        $booking1 = $resource->createBookingFor($this->canBookResources(), [
            'starts_at' => now(),
            'ends_at' => now()->endOfDay(),
        ]);

        $this->assertCount(1, Booking::get());
        $this->assertCount(1, $resource->bookings()->get());

        $resource->delete();

        $this->assertCount(0, Booking::get());
    }

    /** @test */
    public function bookable_resource_can_be_checked_for_availability(): void
    {
        $resource = $this->bookableResource();

        $resource->createBookingFor($this->canBookResources(), [
            'starts_at' => '2021-01-01 10:00',
            'ends_at' => '2021-01-01 11:00',
        ]);

        // Future
        $this->assertTrue($resource->isAvailableAt('2021-03-01 11:00'));
        $this->assertTrue($resource->isAvailableAt('2021-03-01 11:00', true));
        $this->assertTrue($resource->isAvailableAt(Carbon::parse('2021-03-01 11:00')));
        $this->assertTrue($resource->isAvailableAt(Carbon::parse('2021-03-01 11:00'), true));
        $this->assertTrue($resource->isAvailableAt(new DateTime('2021-03-01 11:00')));
        $this->assertTrue($resource->isAvailableAt(new DateTime('2021-03-01 11:00'), true));
        // At the moment of
        $this->assertTrue($resource->isBookedAt('2021-01-01 10:00'));
        $this->assertTrue($resource->isBookedAt('2021-01-01 10:00', true));
        $this->assertTrue($resource->isBookedAt(Carbon::parse('2021-01-01 10:00')));
        $this->assertTrue($resource->isBookedAt(Carbon::parse('2021-01-01 10:00'), true));
        $this->assertTrue($resource->isBookedAt(new DateTime('2021-01-01 10:00')));
        $this->assertTrue($resource->isBookedAt(new DateTime('2021-01-01 10:00'), true));
    }

    /** @test */
    public function bookable_resource_can_be_checked_for_availability_against_cancelled(): void
    {
        $resource = $this->bookableResource();

        $resource->createBookingFor($this->canBookResources(), [
            'starts_at' => '2021-02-01 10:00',
            'ends_at' => '2021-02-01 11:00',
            'canceled_at' => now(),
        ]);

        // Future
        $this->assertTrue($resource->isAvailableAt('2021-03-01 11:00'));
        $this->assertTrue($resource->isAvailableAt('2021-03-01 11:00', true));
        $this->assertTrue($resource->isAvailableAt(Carbon::parse('2021-03-01 11:00')));
        $this->assertTrue($resource->isAvailableAt(Carbon::parse('2021-03-01 11:00'), true));
        $this->assertTrue($resource->isAvailableAt(new DateTime('2021-03-01 11:00')));
        $this->assertTrue($resource->isAvailableAt(new DateTime('2021-03-01 11:00'), true));
        // At the moment of
        $this->assertFalse($resource->isBookedAt('2021-02-01 10:00'));
        $this->assertTrue($resource->isBookedAt('2021-02-01 10:00', true));
        $this->assertFalse($resource->isBookedAt(Carbon::parse('2021-02-01 10:00')));
        $this->assertTrue($resource->isBookedAt(Carbon::parse('2021-02-01 10:00'), true));
        $this->assertFalse($resource->isBookedAt(new DateTime('2021-02-01 10:00')));
        $this->assertTrue($resource->isBookedAt(new DateTime('2021-02-01 10:00'), true));
    }

    /** @test */
    public function bookable_resource_can_be_checked_for_availability_using_closue(): void
    {
        $resource = $this->bookableResource();

        $this->assertTrue($resource->isAvailableFor($this->canBookResources(), fn ($m, $r) => true));
        $this->assertFalse($resource->isAvailableFor($this->canBookResources(), fn ($m, $r) => false));
    }

    /**
     * @return \BinaryCats\Coordinator\Tests\Models\CanBookResourcesModel
     */
    protected function canBookResources(): CanBookResourcesModel
    {
        return CanBookResourcesModel::first();
    }

    /**
     * @return \BinaryCats\Coordinator\Tests\Models\BookableResourceModel
     */
    protected function bookableResource(): BookableResourceModel
    {
        return BookableResourceModel::first();
    }
}
