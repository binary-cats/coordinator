<?php

namespace Tests\Unit;

use BinaryCats\Coordinator\Coordinator;
use Spatie\Period\Boundaries;
use Spatie\Period\Precision;
use Tests\TestCase;

class CoordinatorTest extends TestCase
{
    /** @test */
    public function is_matches_config_model(): void
    {
        $value = Coordinator::bookingModel();

        $this->assertEquals($this->app['config']->get('coordinator.booking_model'), $value);
    }

    /** @test */
    public function is_matches_precision(): void
    {
        $value = Coordinator::defaultPrecision();

        $this->assertInstanceOf(Precision::class, $value);
        $this->assertSame($this->app['config']->get('coordinator.precision'), $value);
    }

    /** @test */
    public function is_matches_boundaries(): void
    {
        $value = Coordinator::defaultBoundaries();

        $this->assertInstanceOf(Boundaries::class, $value);
        $this->assertSame($this->app['config']->get('coordinator.boundaries'), $value);
    }
}
