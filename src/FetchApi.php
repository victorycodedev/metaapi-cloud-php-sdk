<?php

namespace Victorycodedev\MetaapiCloudPhpSdk;

use Exception;
use Psr\Http\Message\ResponseInterface;
use Victorycodedev\MetaapiCloudPhpSdk\Exceptions\BadRequestException;
use Victorycodedev\MetaapiCloudPhpSdk\Exceptions\ForbiddenRequestException;
use Victorycodedev\MetaapiCloudPhpSdk\Exceptions\NotFoundException;
use Victorycodedev\MetaapiCloudPhpSdk\Exceptions\TooManyRequestException;
use Victorycodedev\MetaapiCloudPhpSdk\Exceptions\UnauthorizedException;

trait FetchApi
{

    public function get(string $uri)
    {
        return $this->request('GET', $uri);
    }

    public function post(string $uri, array $payload = [])
    {
        return $this->request('POST', $uri, $payload);
    }

    public function put(string $uri, array $payload = [])
    {
        return $this->request('PUT', $uri, $payload);
    }

    public function delete(string $uri, array $payload = [])
    {
        return $this->request('DELETE', $uri, $payload);
    }

    public function request(string $verb, string $uri, array $payload = [])
    {
        $response = $this->client->request(
            $verb,
            $uri,
            empty($payload) ? [] : [
                'body' => json_encode($payload),
                'content-type' => 'application/json',
            ]
        );

        if (!$this->isSuccessful($response)) {
            return $this->handleRequestError($response);
        }

        $responseBody = (string)$response->getBody();

        return json_decode($responseBody, true) ?: $responseBody;
    }

    public function isSuccessful($response): bool
    {
        if (!$response) {
            return false;
        }

        return (int)substr($response->getStatusCode(), 0, 1) === 2;
    }

    public function handleRequestError(ResponseInterface $response): void
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
