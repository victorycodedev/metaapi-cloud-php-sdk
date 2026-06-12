<?php

namespace Victorycodedev\MetaapiCloudPhpSdk\Resources\Copyfactory;

use Victorycodedev\MetaapiCloudPhpSdk\Http;

class Trading
{
    public function __construct(private readonly Http $http)
    {
    }

    public function signals(string $subscriberId): array|string|null
    {
        return $this->http->get("/users/current/subscribers/{$subscriberId}/signals");
    }

    public function externalSignals(string $strategyId): array|string|null
    {
        return $this->http->get("/users/current/strategies/{$strategyId}/external-signals");
    }

    public function updateExternalSignal(string $strategyId, string $signalId, array $data): array|string|null
    {
        return $this->http->put("/users/current/strategies/{$strategyId}/external-signals/{$signalId}", $data);
    }

    public function removeExternalSignal(string $strategyId, string $signalId, array $data): array|string|null
    {
        return $this->http->post("/users/current/strategies/{$strategyId}/external-signals/{$signalId}/remove", $data);
    }

    public function stopouts(string $subscriberId): array|string|null
    {
        return $this->http->get("/users/current/subscribers/{$subscriberId}/stopouts");
    }

    public function resetSubscriptionStopouts(string $subscriberId, string $strategyId, string $reason): array|string|null
    {
        return $this->http->post("/users/current/subscribers/{$subscriberId}/subscription-strategies/{$strategyId}/stopouts/{$reason}/reset");
    }

    public function resetSubscriberStopouts(string $subscriberId, string $reason): array|string|null
    {
        return $this->http->post("/users/current/subscribers/{$subscriberId}/stopouts/{$reason}/reset");
    }

    public function stopoutsStream(array $query = []): array|string|null
    {
        return $this->http->get('/users/current/stopouts/stream', $query);
    }

    public function resynchronize(string $subscriberId, array $query = []): array|string|null
    {
        return $this->http->post("/users/current/subscribers/{$subscriberId}/resynchronize", query: $query);
    }
}
