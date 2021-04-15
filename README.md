# Coordinator

**Coordinator** is a generic resource booking system for Laravel, built with simplicity in mind. It will allow you to book (or schedule) resources using simple intuitive API.

![https://github.com/binary-cats/coordinator/actions](https://github.com/binary-cats/coordinator/workflows/Laravel/badge.svg)
![https://github.styleci.io/repos/358098480](https://github.styleci.io/repos/358098480/shield)
![https://scrutinizer-ci.com/g/binary-cats/coordinator/](https://scrutinizer-ci.com/g/binary-cats/coordinator/badges/quality-score.png?b=master)

The package is built with expectations that you would probably extending it for you own needs, so very few conventions are used. Please make sure to familiarize yourselves with the [concepts](#concepts).

## Installation

You can install the package via composer:

```bash
composer require binary-cats/coordinator
```
The Service Provider will automatically register itself and add the following configuration to your Laravel installation:

```php
use BinaryCats\Coordinator\Eloquent\Booking;
use Spatie\Period\Boundaries;
use Spatie\Period\Precision;

return [

    /*
    |--------------------------------------------------------------------------
    | Coordinator: Booking Model
    |--------------------------------------------------------------------------
    |
    | The default is `BinaryCats\Coordinator\Eloquent\Booking`.
    | You are likely to extend the class or replace it with your implementation:
    | Model must implement `BinaryCats\Coordinator\Contracts\Booking`
    |
    */

    'booking_model' => Booking::class,

    /*
    |--------------------------------------------------------------------------
    | Booking Period: Precision
    |--------------------------------------------------------------------------
    | Date precision is important if you want to reliably compare two periods:
    | @see https://stitcher.io/blog/comparing-dates
    |
    | Valid options are:
    | YEAR|DAY|HOUR|MINUTE|SECOND
    */

   'precision' => Precision::SECOND(),

   /*
    |--------------------------------------------------------------------------
    | Booking Period: Boundaries
    |--------------------------------------------------------------------------
    | By default, period comparisons are done with included boundaries
    |
    | Valid options are:
    | EXCLUDE_NONE|EXCLUDE_START|EXCLUDE_END|EXCLUDE_ALL
    */

   'boundaries' => Boundaries::EXCLUDE_NONE(),
];

```

To change anything in the setup, you can publish the config file using

```bash
php artisan vendor:publish --tag=coordinator-config
```
Config file will be published at `config/coordinator.php`:

## Migration

Running the following will publish a single migration for bookings table:
```bash
php artisan vendor:publish --tag=coordinator-migrations
```

### Concepts

The package provides you with a single model: Booking.

#### Booking Model

`Booking` is a single record that ties together bookable resource, like a `Room` or `Table` or `TechSupport` and the model that books the resource. Since the word *book* is present in every word, it may be hard to grasp right away. In essence, `Booking` is a _pivot model_, that has two polymorphic keys: `model` which represents the owner of the relationship and `resource`, representing, well, a resource we want to book.

A simple example, perhaps, will help:

When a *customer* wants to rent an *room*, we have a relationship between a Customer and a Resource they want.

Customer is a `model` and a Room is a `resource` and their relationship is stored as a `Booking`.

#### Availability

It is not expected that you may want to have overlapping booking of resources, but nothing should stop you from trying!

There are legitimate use cases when you may want to create two bookings and whomever confirms first, gets the slot and the other gets cancelled.

**Coordinator** will not try to force your hand, but that means that you need to implement this logic yorself.

### Usage

Let's prepare our models. First, the _resource that can be booked_:

```php

use BinaryCats\Coordinator\CanBeBooked;
use BinaryCats\Coordinator\Contracts\BookableResource;
use Illuminate\Database\Eloquent\Model;

class Room extends Model implements BookableResource
{
    use CanBeBooked;
}
```
Note that Resource is implementing `BinaryCats\Coordinator\Contracts\BookableResource`:

Now, a model that _books resources_:
```php

use BinaryCats\Coordinator\BooksResources;
use BinaryCats\Coordinator\Contracts\BookableResource;
use Illuminate\Database\Eloquent\Model;

class User extends Model implements BookableResource
{
    use BooksResources;
}
```

**Coordinator** offers fluent interface to create a booking, that can be used on either side of the relation:
```php
    $booking = User::first()->createBookingFor(Room::first(), [
        'starts_at' => '2021-01-01 15:45',
        'ends_at' => '2021-01-02 15:45',
    ]);
```
and
```php
    $booking = Room::first()->createBookingFor(User::first(), [
        'starts_at' => '2021-01-01 15:45',
        'ends_at' => '2021-01-02 15:45',
    ]);
```
will produce identical result.

### Checking availability

Bookable resource provides means of checking if it is available or booked at a given moment:

To check if the `Room` is available at a given moment with `isAvailableAt`. The first argument can be any implementation of `DateTimeInterface` that can be converted to `Carbon\Carbon` instance, a string or even a Period:

```php
Room::first()->isAvailableAt(new DateTime);
Room::first()->isAvailableAt(Carbon\Carbon::now());
Room::first()->isAvailableAt('2021-01-01 15:45');
Room::first()->isAvailableAt('2021-01-01 3:45 PM CST');

```

To check if the `Room` is available on '2021-01-01', you can use `isAvailableAt`:

```php
    // bookings = [
    //      [
    //          ...
    //          'starts_at' => '2021-01-01',
    //          'ends_at' => '2021-01-02',
    //          'cancelled_at' => '2021-04-02'
    //      ]
    // ];
    Room::first()->isAvailableAt('2021-01-01');
    // true;
```

You can pass the second argument to account for the bookings that are canceled:
```php
    // bookings = [
    //      [
    //          ...
    //          'starts_at' => '2021-01-01',
    //          'ends_at' => '2021-01-02',
    //          'cancelled_at' => '2021-04-02'
    //      ]
    // ];
    Room::first()->isAvailableAt('2021-01-01', true);
    // false
```

You can check if the `Room` is booked at a given moment with `isBookedAt`:
```php
    // bookings = [
    //      [
    //          ...
    //          'starts_at' => '2021-01-01',
    //          'ends_at' => '2021-01-02',
    //          'cancelled_at' => '2021-04-02'
    //      ]
    // ];
    Room::first()->isBookedAt('2021-01-01');
    // false;
```
You can pass the second argument to account for the bookings that are canceled:
```php
    // bookings = [
    //      [
    //          ...
    //          'starts_at' => '2021-01-01',
    //          'ends_at' => '2021-01-02',
    //          'cancelled_at' => '2021-04-02'
    //      ]
    // ];
    Room::first()->isBookedAt('2021-01-01', true);
    // true;
```

## Advanced usage

You can check if the Resource is booked or available against a period as well. When doing so, you need to make sure you are comparing period _with the same precision_. The easiest way is to simply rely on the setting of the **Coordinator** itself:

```php
use BinaryCats\Coordinator\Coordinator;
use Spatie\Period\Period;

    $period = Period::make('2021-01-01 15:45:00', '2021-01-02 15:45:00', Coordinator::defaultPrecision());

    // bookings = [
    //      [
    //          ...
    //          'starts_at' => '2021-01-01 10:00:00',
    //          'ends_at' => '2021-01-02 09:00:00',
    //      ]
    // ];
    Room::first()->isBookedAt($period);
    // true;
```
By default Coordinator will look for `SECOND` as precison. You can change that by updating `coordinator.precision` config key.
More about period calculations [here](https://github.com/spatie/period);

You can also check if the Resource is available for a given Model. However, since this is more of a policy concern, you need to provide closure resolution logic. For example, you may want to defer to authorization logic using a Gate:

```php
use Illuminate\Support\Facades\Gate;

Room::first()->isAvailableFor(User::first(), function($model, $resource) {
    // Assuming model is \Illuminate\Contracts\Auth\Access\Authorizable
    return Gate::forUser($model)->allows('book', $resource);
});
```
or anything else:

```php
Room::first()->isAvailableFor(User::first(), function($model, $resource) {
    // ... return resolution
});
```

Lastly, remember, that just like with notifications, you should not be bound by thinking "it is a user that books something". Any model can be booking any other resource; _a Driver user can be booked by a Delivery_ for instance.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information about what has changed recently.

## Testing

```bash
composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email cyrill.kalita@gmail.com instead of using issue tracker.

## Postcardware

You're free to use this package, but if it makes it to your production environment we highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using.

## Credits

- [Cyrill Kalita](https://github.com/binary-cats)
- [All Contributors](../../contributors)

## Support us

Binary Cats is a webdesign agency based in Illinois, US.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
