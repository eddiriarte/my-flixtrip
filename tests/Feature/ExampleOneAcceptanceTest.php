<?php

declare(strict_types=1);

test('Tip can be created with 10 spots', function () {
    $this->post('/api/v1/trips', ['slots' => 10])
        ->assertStatusCode(201);
});

test('John can reserve 2 spots', function () {
    $trip = $this->post('/api/v1/trips', ['slots' => 10])
        ->assertStatusCode(201)
        ->data(['trip_id']);

    $reservationJohn = $this->post(
        "/api/v1/trips/{$trip['trip_id']}/reservations",
        ['slots' => 2, 'customer' => 'John']
    )->assertStatusCode(201)
        ->data(['reservation_id']);
});


test('Jane can reserve 8 spots', function () {
    $trip = $this->post('/api/v1/trips', ['slots' => 10])
        ->assertStatusCode(201)
        ->data(['trip_id']);

    $reservationJohn = $this->post(
        "/api/v1/trips/{$trip['trip_id']}/reservations",
        ['slots' => 2, 'customer' => 'John']
    )->assertStatusCode(201)
        ->data(['reservation_id']);

    $reservationJane = $this->post(
        "/api/v1/trips/{$trip['trip_id']}/reservations",
        ['slots' => 8, 'customer' => 'Jane']
    )->assertStatusCode(201)
        ->data(['reservation_id']);
});

test('Ronald can not reserve 1 spots', function () {
    $trip = $this->post('/api/v1/trips', ['slots' => 10])
        ->assertStatusCode(201)
        ->data(['trip_id']);

    $reservationJohn = $this->post(
        "/api/v1/trips/{$trip['trip_id']}/reservations",
        ['slots' => 2, 'customer' => 'John']
    )->assertStatusCode(201)
        ->data(['reservation_id']);

    $reservationJane = $this->post(
        "/api/v1/trips/{$trip['trip_id']}/reservations",
        ['slots' => 8, 'customer' => 'Jane']
    )->assertStatusCode(201)
        ->data(['reservation_id']);

    $this->expectExceptionCode(422);
    $this->expectException(\App\Application\Exceptions\ValidationException::class);

    $reservationRonald = $this->post(
        "/api/v1/trips/{$trip['trip_id']}/reservations",
        ['slots' => 1, 'customer' => 'Ronald']
    )->assertStatusCode(422);
});
