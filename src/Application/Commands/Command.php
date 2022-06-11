<?php

declare(strict_types=1);

namespace App\Application\Commands;

interface Command
{
    public function handle(array $data): mixed;
}
