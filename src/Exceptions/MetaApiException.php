<?php

namespace Victorycodedev\MetaapiCloudPhpSdk\Exceptions;

use Exception;
use Throwable;

class MetaApiException extends Exception
{
    public function __construct(
        string $message = '',
        int $code = 0,
        private readonly int $statusCode = 0,
        private readonly array|string|null $response = null,
        private readonly array $headers = [],
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function statusCode(): int
    {
        return $this->statusCode;
    }

    public function response(): array|string|null
    {
        return $this->response;
    }

    public function headers(): array
    {
        return $this->headers;
    }

    public function errorId(): ?int
    {
        return is_array($this->response) && isset($this->response['id'])
            ? (int) $this->response['id']
            : null;
    }

    public function errorName(): ?string
    {
        return is_array($this->response) && isset($this->response['error'])
            ? (string) $this->response['error']
            : null;
    }

    public function details(): mixed
    {
        return is_array($this->response) && array_key_exists('details', $this->response)
            ? $this->response['details']
            : null;
    }

    public function retryAfter(): ?string
    {
        return $this->headers['Retry-After'][0] ?? $this->headers['retry-after'][0] ?? null;
    }
}
