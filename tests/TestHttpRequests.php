<?php

namespace Tests;

use PHPUnit\Framework\Assert;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\StreamInterface;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Request as SlimRequest;
use Slim\Psr7\Uri;

trait TestHttpRequests
{
    protected function request(
        string $method,
        string $path,
        array $headers = ['HTTP_ACCEPT' => 'application/json'],
        array $cookies = [],
        array $serverParams = []
    ): Request {
        $uri = new Uri('', '', 80, $path);
        $handle = fopen('php://temp', 'w+');
        $stream = (new StreamFactory())->createStreamFromResource($handle);

        $h = new Headers();
        foreach ($headers as $name => $value) {
            $h->addHeader($name, $value);
        }

        return new SlimRequest($method, $uri, $h, $cookies, $serverParams, $stream);
    }

    public function post(
        string $path,
        array $body = [],
        array $headers = ['HTTP_ACCEPT' => 'application/json'],
        array $cookies = [],
        array $serverParams = []
    ): ResponseInterface {
        $app = $this->getAppInstance();

        $request = $this->request('POST', $path, $headers, $cookies, $serverParams)
            ->withParsedBody($body);

        $response = $app->handle($request);

        return $this->makeResponseFacade($response);
    }

    public function put(
        string $path,
        array $body = [],
        array $headers = ['HTTP_ACCEPT' => 'application/json'],
        array $cookies = [],
        array $serverParams = []
    ): ResponseInterface {
        $app = $this->getAppInstance();

        $request = $this->request('PUT', $path, $headers, $cookies, $serverParams)
            ->withParsedBody($body);

        $response = $app->handle($request);

        return $this->makeResponseFacade($response);
    }

    public function delete(
        string $path,
        array $body = [],
        array $headers = ['HTTP_ACCEPT' => 'application/json'],
        array $cookies = [],
        array $serverParams = []
    ): ResponseInterface {
        $app = $this->getAppInstance();

        $request = $this->request('DELETE', $path, $headers, $cookies, $serverParams)
            ->withParsedBody($body);

        $response = $app->handle($request);

        return $this->makeResponseFacade($response);
    }

    private function makeResponseFacade(ResponseInterface $response): ResponseInterface
    {
        return new class($response) implements ResponseInterface {
            public function __construct(private ResponseInterface $response)
            {
            }

            public function data(array $keys = []): array
            {
                $serializedBody = (string)$this->getBody();
                $data = json_decode($serializedBody, true)['data'] ?? [];

                if (count($keys) > 0) {
                    return array_filter(
                        $data,
                        fn (string $key) => in_array($key, $keys),
                        ARRAY_FILTER_USE_KEY
                    );
                }

                return $data;
            }

            public function assertStatusCode(int $expectedStatusCode): static
            {
                Assert::assertEquals($expectedStatusCode, $this->getStatusCode());

                return $this;
            }



            public function assertBody(array $expectedBody = []): static
            {
                Assert::assertEquals($expectedBody, $this->data());

                return $this;
            }

            // Forward all calls to the original object...

            public function getProtocolVersion()
            {
                return $this->response->getProtocolVersion();
            }

            public function withProtocolVersion($version)
            {
                return $this->response->withProtocolVersion($version);
            }

            public function getHeaders()
            {
                return $this->response->getHeaders();
            }

            public function hasHeader($name)
            {
                return $this->response->hasHeader($name);
            }

            public function getHeader($name)
            {
                return $this->response->getHeader($name);
            }

            public function getHeaderLine($name)
            {
                $this->response->getHeaderLine($name);
            }

            public function withHeader($name, $value)
            {
                $this->response->withHeader($name, $value);
            }

            public function withAddedHeader($name, $value)
            {
                $this->response->withAddedHeader($name, $value);
            }

            public function withoutHeader($name)
            {
                $this->response->withoutHeader($name);
            }

            public function getBody()
            {
                return $this->response->getBody();
            }

            public function withBody(StreamInterface $body)
            {
                $this->response->withBody($body);
            }

            public function getStatusCode()
            {
                return $this->response->getStatusCode();
            }

            public function withStatus($code, $reasonPhrase = '')
            {
                $this->response->withStatus($code, $reasonPhrase);

                return $this;
            }

            public function getReasonPhrase()
            {
                return $this->response->getReasonPhrase();
            }
        };
    }
}
