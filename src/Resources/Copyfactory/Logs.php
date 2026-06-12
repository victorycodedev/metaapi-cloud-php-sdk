<?php

namespace Victorycodedev\MetaapiCloudPhpSdk\Resources\Copyfactory;

use Victorycodedev\MetaapiCloudPhpSdk\Http;

class Logs
{
    public function __construct(private readonly Http $http)
    {
    }

    public function userLog(string $subscriberId, array $query = []): array|string|null
    {
        return $this->http->get("/users/current/subscribers/{$subscriberId}/user-log", $query);
    }

    public function userLogStream(string $subscriberId, array $query = []): array|string|null
    {
        return $this->http->get("/users/current/subscribers/{$subscriberId}/user-log/stream", $query);
    }

    public function strategyLog(string $strategyId, array $query = []): array|string|null
    {
        return $this->http->get("/users/current/strategies/{$strategyId}/user-log", $query);
    }

    public function strategyLogStream(string $strategyId, array $query = []): array|string|null
    {
        return $this->http->get("/users/current/strategies/{$strategyId}/user-log/stream", $query);
    }
}
