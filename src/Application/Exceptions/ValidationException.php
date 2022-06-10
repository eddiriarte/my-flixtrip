<?php

declare(strict_types=1);

namespace App\Application\Exceptions;

use Rakit\Validation\Validation;

class ValidationException extends \Exception
{
    public function __construct(private Validation $validation)
    {
        parent::__construct('Data sent is not valid.', 422);
    }

    public function getErrors(): array
    {
        return $this->validation->errors->all();
    }
}
