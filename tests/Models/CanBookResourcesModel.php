<?php

namespace Tests\Models;

use BinaryCats\Coordinator\BooksResources;
use BinaryCats\Coordinator\Contracts\CanBookResources;
use Illuminate\Database\Eloquent\Model;

class CanBookResourcesModel extends Model implements CanBookResources
{
    use BooksResources;
}
