<?php

declare(strict_types=1);

namespace App\Application\Actions\Api;

use App\Application\Actions\JsonResponse;
use App\Application\Exceptions\ValidationException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rakit\Validation\Validator;

class CreateTrip
{
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        $data = $this->validated($request->getParsedBody());

        return (new JsonResponse($response))->send($data);
    }

    private function validated(array $parameters): array
    {
        $validation = (new Validator())
            ->validate(
                $parameters,
                [
                    'origin' => 'nullable|alpha_spaces',
                    'destiny' => 'nullable|alpha_spaces',
                    'slots' => 'required|integer',
                ]
            );

        if ($validation->fails()) {
            throw new ValidationException($validation);
        }

        return $validation->getValidData();
    }
}
