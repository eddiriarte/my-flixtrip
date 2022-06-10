<?php

declare(strict_types=1);

use App\Application\Projections\TripRepository;
use App\Infrastructure\Persistence\Booking\DatabaseTripRepository;
use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder) {
    // Here we map our UserRepository interface to its in memory implementation
    $containerBuilder->addDefinitions([
        TripRepository::class => \DI\autowire(DatabaseTripRepository::class),
    ]);
};
