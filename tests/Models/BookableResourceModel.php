<?php

namespace BinaryCats\Coordinator\Tests\Models;

use BinaryCats\Coordinator\CanBeBooked;
use BinaryCats\Coordinator\Contracts\BookableResource;
use Illuminate\Database\Eloquent\Model;

class BookableResourceModel extends Model implements BookableResource
{
    use CanBeBooked;
}
