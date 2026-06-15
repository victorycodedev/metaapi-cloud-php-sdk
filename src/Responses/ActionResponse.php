<?php

namespace Victorycodedev\MetaapiCloudPhpSdk\Responses;

class ActionResponse
{
    public function __construct(
        private readonly array|string|null $body,
        private readonly int $statusCode,
        private readonly array $headers = []
    ) {}

    public function body(): array|string|null
    {
        return $this->body;
    }

    public function statusCode(): int
    {
        return $this->statusCode;
    }

    public function headers(): array
    {
        return $this->headers;
    }

    public function retryAfter(): ?string
    {
        return $this->header('Retry-After') ?? $this->header('retry-after');
    }

    public function isCreated(): bool
    {
        return $this->statusCode === 201;
    }

    public function isAccepted(): bool
    {
        return $this->statusCode === 202;
    }

    public function shouldRetry(): bool
    {
        return $this->isAccepted() || $this->retryAfter() !== null;
    }

    public function id(): ?string
    {
        return $this->field('id');
    }

    public function state(): ?string
    {
        return $this->field('state');
    }

    private function header(string $name): ?string
    {
        $values = $this->headers[$name] ?? null;

        return is_array($values) && $values !== [] ? (string) $values[0] : null;
    }

    private function field(string $name): ?string
    {
        return is_array($this->body) && isset($this->body[$name])
            ? (string) $this->body[$name]
            : null;
    }
}
