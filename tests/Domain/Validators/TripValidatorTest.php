<?php

declare(strict_types=1);

namespace Tests\Domain\Validators;

use App\Application\Exceptions\ValidationException;
use App\Domain\Booking\Validators\TripValidator;
use Tests\TestCase;

class TripValidatorTest extends TestCase
{
    /** @dataProvider provideValues */
    public function testValidation(array $data, array $expectedValues)
    {
        $validator = new TripValidator();

        $validated = $validator->validate($data);

        $this->assertEquals(
            $expectedValues,
            $validated
        );
    }

    public function provideValues(): \Generator
    {
        yield [
            ['slots' => 10],
            ['slots' => 10, 'origin' => null, 'destination' => null],
        ];

        yield [
            ['slots' => 10, 'origin' => 'Paris'],
            ['slots' => 10, 'origin' => 'Paris', 'destination' => null],
        ];

        yield [
            ['slots' => 10, 'destination' => 'Paris'],
            ['slots' => 10, 'origin' => null, 'destination' => 'Paris'],
        ];

        yield [
            ['slots' => 10, 'origin' => 'Berlin', 'destination' => 'Paris'],
            ['slots' => 10, 'origin' => 'Berlin', 'destination' => 'Paris'],
        ];
    }

    public function testValidationException()
    {
        $validator = new TripValidator();

        $this->expectExceptionCode(422);
        $this->expectException(ValidationException::class);

        $validated = $validator->validate(['origin' => 'Berlin']);
    }
}
