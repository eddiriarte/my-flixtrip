<?php

declare(strict_types=1);

use Phoenix\Migration\AbstractMigration;

final class CreateTripEventsTable extends AbstractMigration
{
    protected function up(): void
    {
        $this->execute('CREATE TABLE IF NOT EXISTS `trip_events` (
          `event_id` BINARY(16) NOT NULL,
          `aggregate_root_id` BINARY(16) NOT NULL,
          `version` int(20) unsigned NULL,
          `payload` varchar(16001) NOT NULL,
          PRIMARY KEY (`event_id`),
          KEY (`aggregate_root_id`),
          KEY `reconstitution` (`aggregate_root_id`, `version` ASC)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE=InnoDB');
    }

    protected function down(): void
    {
        $this->table('trip_events')->drop();
    }
}
