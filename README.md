My FlixTrip
===========

This is a small application(for demo purpose only) which allow customers to book city trips available in the platform.
All changes are stored using event-sourcing and CQRS in order to provide a resilient data structure and boost performance of reading requests.  

__Tech. Stack__

PHP 8.1 (SlimPHP Framework) + Mysql

## Start Application

__1) Start containers__

```
docker-compose up -d
```

__2) Install all dependencies__

```
docker-compose exec app composer install

# alternative: Run composer install locally on your host machine. (requires PHP 8.1 + mysql module)
```

__3) Run migrations to setup inital DB Schema__ 

```
docker-compose exec app vendor/bin/phoenix migrate
```

That's it! Now go build something cool.

## Testing Application

__1) Endpoints__ 

The application contains following endpoints:

| Method | Path | Description                                                                    |
|--------|------|--------------------------------------------------------------------------------|
| POST | `/api/v1/trips` | Create a new trip (origin - destination cities) and number of available slots. |
| GET | `/api/v1/trips` | RProvides a list all available trips.                                          |
| POST | `/api/v1/trips/{{trip_id}}/reservations` | Place a reservation for trip (requires customer name and number of slots)      |
| DELETE | `/api/v1/trips/{{trip_id}}/reservations/{{reservation_id}}` | Cancels an existing reservation. All slots are availablke for booking again.   |
| PUT | `/api/v1/trips/{{trip_id}}/reservations/{{reservation_id}}` | Adapts an existing reservation (only number of slots). |

The easiest way to test the endpoints is to run the call defined in the `MyFlixTrip.http` file. (Executable in PHPStorm)

__2) Unit-Tests and E2E-Tests__

```
docker-compose exec app vendor/bin/pest
```

Currently tests are not working on Github due to database dependencies. 
A fix hat has not been implemented, but all tests are passing, when running them as described. 

