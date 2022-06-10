<?php

declare(strict_types=1);

use Phoenix\Migration\AbstractMigration;

final class CreateTripAndReservationTables extends AbstractMigration
{
    protected function up(): void
    {
        $this->execute('CREATE TABLE trips (
            id VARCHAR(64) NOT NULL,
            origin VARCHAR(255) NULL,
            destiny VARCHAR(255) NULL,
            slots INT NOT NULL,
            free_slots INT NOT NULL,
            PRIMARY KEY(id)
        ) ENGINE = InnoDB;');
        $this->execute('CREATE TABLE reservations (
            id VARCHAR(64) NOT NULL,
            trip_id VARCHAR(64) NULL,
            customer VARCHAR(255) NULL,
            slots INT NOT NULL,
            PRIMARY KEY(id)
        ) ENGINE = InnoDB;');
        $this->execute('ALTER TABLE reservations ADD FOREIGN KEY (trip_id) REFERENCES trips(id);');
    }

    protected function down(): void
    {
        $this->table('reservations')->drop();
        $this->table('trips')->drop();
    }
}
