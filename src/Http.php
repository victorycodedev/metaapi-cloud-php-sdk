<?php

namespace Victorycodedev\MetaapiCloudPhpSdk;

use Exception;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Victorycodedev\MetaapiCloudPhpSdk\Exceptions\BadRequestException;
use Victorycodedev\MetaapiCloudPhpSdk\Exceptions\ForbiddenRequestException;
use Victorycodedev\MetaapiCloudPhpSdk\Exceptions\NotFoundException;
use Victorycodedev\MetaapiCloudPhpSdk\Exceptions\TooManyRequestException;
use Victorycodedev\MetaapiCloudPhpSdk\Exceptions\UnauthorizedException;

class Http
{
    public function __construct(private string $token)
    {
    }

    public function get(string $uri): array|string
    {
        return $this->request('GET', $uri,);
    }

    public function post(string $uri, array $payload = []): array|string
    {
        return $this->request('POST', $uri,  $payload);
    }

    public function put(string $uri, array $payload = []): array|string
    {
        return $this->request('PUT', $uri,  $payload);
    }

    public function delete(string $uri, array $payload = []): array|string
    {
        return $this->request('DELETE', $uri,  $payload);
    }

    /**
     *  Send a request to the MetaApi API.
     */
    public function request(string $verb, string $uri, array $payload = []): array|string
    {
        $client = new Client([
            'http_errors' => false,
            'headers' => [
                'auth-token' => $this->token,
                'Accept' => 'application/json',
            ],
        ]);

        $response = $client->request(
            $verb,
            $uri,
            empty($payload) ? [] : [
                'body' => json_encode($payload),
            ]
        );

        if (!$this->isSuccessful($response)) {
            return $this->handleError($response);
        }

        $responseBody = (string)$response->getBody();

        return json_decode($responseBody, true) ?: $responseBody;
    }

    public function isSuccessful($response): bool
    {
        if (!$response) {
            return false;
        }

        return $response->getStatusCode() >= 200 && $response->getStatusCode() < 300;
    }

    public function handleError(ResponseInterface $response): void
    {
        match ($response->getStatusCode()) {
            400 => throw new BadRequestException((string)$response->getBody()),
            401 => throw new UnauthorizedException((string)$response->getBody()),
            403 => throw new ForbiddenRequestException((string)$response->getBody()),
            404 => throw new NotFoundException((string)$response->getBody()),
            429 => throw new TooManyRequestException((string)$response->getBody()),
            default => throw new Exception((string)$response->getBody()),
        };
    }
}
