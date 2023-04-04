<?php

namespace Victorycodedev\MetaapiCloudPhpSdk\Resources\Copyfactory;

trait Configuration
{
    private string $configUrl = "users/current/configuration";

    /*
    *   Generates a new strategy id
    */
    public function generateStrategyId(): array|string
    {
        return $this->http->get("/{$this->configUrl}/unused-strategy-id");
    }

    /*
    *   Returns provider strategies the user has configured
    */
    public function strategies(bool $includeRemoved = false, int $limit = 1000, int $offset = 0): array|string
    {
        return $this->http->get("/{$this->configUrl}/strategies?includeRemoved={$includeRemoved}&limit={$limit}&offset={$offset}");
    }

    /*
    *   Returns provider strategy the user has configured by id
    */
    public function strategy(string $strategyId): array|string
    {
        return $this->http->get("/{$this->configUrl}/strategies/{$strategyId}");
    }

    /*
    *   Updates provider strategy
    */
    public function updateStrategy(string $strategyId, array $data): array|string
    {
        return $this->http->put("/{$this->configUrl}/strategies/{$strategyId}", $data);
    }

    /*
    *   Deletes provider strategy
    */
    public function removeStrategy(string $strategyId): array|string
    {
        return $this->http->delete("/{$this->configUrl}/strategies/{$strategyId}");
    }

    /*
    *   Returns strategy subscribers the current user provides strategies to
    */
    public function subscribers(bool $includeRemoved = false, int $limit = 1000, int $offset = 0): array|string
    {
        return $this->http->get("/{$this->configUrl}/subscribers?includeRemoved={$includeRemoved}&limit={$limit}&offset={$offset}");
    }

    /*
    *   Returns CopyFactory subscriber by id
    */
    public function subscriber(string $subscriberId): array|string
    {
        return $this->http->get("/{$this->configUrl}/subscribers/{$subscriberId}");
    }

    /*
    *   Updates subscriber configuration
    */

    public function updateSubscriber(string $subscriberId, array $data): array|string
    {
        return $this->http->put("/{$this->configUrl}/subscribers/{$subscriberId}", $data);
    }

    /*
    *   Deletes subscriber configuration
    */
    public function removeSubscriber(string $subscriberId): array|string
    {
        return $this->http->delete("/{$this->configUrl}/subscribers/{$subscriberId}");
    }

    /*
    *   Deletes subscription
    */

    public function deleteSubscription(string $subscriberId, string $strategyId): array|string
    {
        return $this->http->delete("/{$this->configUrl}/subscribers/{$subscriberId}/subscriptions/{$strategyId}");
    }
}
