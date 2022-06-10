<?php

namespace Tests\Application\Actions\Api;

use App\Application\Actions\ActionPayload;
use Tests\TestCase;

class CreateTripTest extends TestCase
{
    public function testInvokedAction()
    {
        $app = $this->getAppInstance();



        $request = $this->createRequest(
            'POST',
            '/api/v1/trips',
        );
        $response = $app->handle($request);

        $payload = (string) $response->getBody();

        $expectedPayload = json_encode(new ActionPayload(200, 'Hello World!'));

        $this->assertEquals($expectedPayload, $payload);


        $this->assertTrue(true);
    }
}
