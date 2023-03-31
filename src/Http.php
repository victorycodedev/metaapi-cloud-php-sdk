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

    public function get(string $uri, string $token): array|string
    {
        return $this->request('GET', $uri, $token);
    }

    public function post(string $uri, string $token, array $payload = []): array|string
    {
        return $this->request('POST', $uri, $token, $payload);
    }

    public function put(string $uri, string $token, array $payload = []): array|string
    {
        return $this->request('PUT', $uri, $token, $payload);
    }

    public function delete(string $uri, string $token, array $payload = []): array|string
    {
        return $this->request('DELETE', $uri, $token, $payload);
    }

    /**
     *  Send a request to the MetaApi API.
     */
    public function request(string $verb, string $uri, string $token, array $payload = []): array|string
    {
        $client = new Client([
            'http_errors' => false,
            'headers' => [
                'auth-token' => $token,
                'Accept' => 'application/json',
            ],
        ]);

        $response = $client->request(
            $verb,
            $uri,
            empty($payload) ? [] : [
                'body' => $payload,
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
            401 => throw new UnauthorizedException(),
            403 => throw new ForbiddenRequestException(),
            404 => throw new NotFoundException(),
            429 => throw new TooManyRequestException(),
            default => throw new Exception((string)$response->getBody()),
        };
    }
}
