<?php

test('Tip can be created with 10 spots', function () {
    $this->post('/api/v1/trips', ['slots' => 10])
        ->assertStatusCode(201);
});

test('John can reserve 8 spots', function () {
    $trip = $this->post('/api/v1/trips', ['slots' => 10])
        ->assertStatusCode(201)
        ->data(['trip_id']);

    $reservationJohn = $this->post(
        "/api/v1/trips/{$trip['trip_id']}/reservations",
        ['slots' => 8, 'customer' => 'John']
    )->assertStatusCode(201)
        ->data(['reservation_id']);
});

test('Jane can reserve 2 spots', function () {
    $trip = $this->post('/api/v1/trips', ['slots' => 10])
        ->assertStatusCode(201)
        ->data(['trip_id']);

    $reservationJohn = $this->post(
        "/api/v1/trips/{$trip['trip_id']}/reservations",
        ['slots' => 8, 'customer' => 'John']
    )->assertStatusCode(201)
        ->data(['reservation_id']);

    $reservationJane = $this->post(
        "/api/v1/trips/{$trip['trip_id']}/reservations",
        ['slots' => 2, 'customer' => 'Jane']
    )->assertStatusCode(201)
        ->data(['reservation_id']);
});

test('Jane can cancel her it reservation', function () {
    $trip = $this->post('/api/v1/trips', ['slots' => 10])
        ->assertStatusCode(201)
        ->data(['trip_id']);

    $reservationJohn = $this->post(
        "/api/v1/trips/{$trip['trip_id']}/reservations",
        ['slots' => 8, 'customer' => 'John']
    )->assertStatusCode(201)
        ->data(['reservation_id']);

    $reservationJane = $this->post(
        "/api/v1/trips/{$trip['trip_id']}/reservations",
        ['slots' => 2, 'customer' => 'Jane']
    )->assertStatusCode(201)
        ->data(['reservation_id']);

    $cancellationJane = $this->delete(
        "/api/v1/trips/{$trip['trip_id']}/reservations/{$reservationJane['reservation_id']}",
    )->assertStatusCode(200);
});

test('Ronald can not reserve 3 spots', function () {
    $trip = $this->post('/api/v1/trips', ['slots' => 10])
        ->assertStatusCode(201)
        ->data(['trip_id']);

    $reservationJohn = $this->post(
        "/api/v1/trips/{$trip['trip_id']}/reservations",
        ['slots' => 8, 'customer' => 'John']
    )->assertStatusCode(201)
        ->data(['reservation_id']);

    $reservationJane = $this->post(
        "/api/v1/trips/{$trip['trip_id']}/reservations",
        ['slots' => 2, 'customer' => 'Jane']
    )->assertStatusCode(201)
        ->data(['reservation_id']);

    $cancellationJane = $this->delete(
        "/api/v1/trips/{$trip['trip_id']}/reservations/{$reservationJane['reservation_id']}",
    )->assertStatusCode(200);

    $this->expectExceptionCode(422);
    $this->expectException(\App\Application\Exceptions\ValidationException::class);

    $reservationRonald = $this->post(
        "/api/v1/trips/{$trip['trip_id']}/reservations",
        ['slots' => 3, 'customer' => 'Ronald']
    )->assertStatusCode(201)
        ->data(['reservation_id']);
});


test('Daniel can reserve 1 spot', function () {
    $trip = $this->post('/api/v1/trips', ['slots' => 10])
        ->assertStatusCode(201)
        ->data(['trip_id']);

    $reservationJohn = $this->post(
        "/api/v1/trips/{$trip['trip_id']}/reservations",
        ['slots' => 8, 'customer' => 'John']
    )->assertStatusCode(201)
        ->data(['reservation_id']);

    $reservationJane = $this->post(
        "/api/v1/trips/{$trip['trip_id']}/reservations",
        ['slots' => 2, 'customer' => 'Jane']
    )->assertStatusCode(201)
        ->data(['reservation_id']);

    $cancellationJane = $this->delete(
        "/api/v1/trips/{$trip['trip_id']}/reservations/{$reservationJane['reservation_id']}",
    )->assertStatusCode(200);

    try {
        $reservationRonald = $this->post(
            "/api/v1/trips/{$trip['trip_id']}/reservations",
            ['slots' => 3, 'customer' => 'Ronald']
        )->assertStatusCode(201)
            ->data(['reservation_id']);

        $this->fail('Exception is expected to be thrown... this line should not be reached');
    } catch (\App\Application\Exceptions\ValidationException $e) {
        $this->assertNotEmpty($e->getErrors());
    }

    $reservationJane = $this->post(
        "/api/v1/trips/{$trip['trip_id']}/reservations",
        ['slots' => 2, 'customer' => 'Daniel']
    )->assertStatusCode(201)
        ->data(['reservation_id']);
});
