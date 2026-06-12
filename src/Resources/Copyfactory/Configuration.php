<?php

namespace Victorycodedev\MetaapiCloudPhpSdk\Resources\Copyfactory;

use Victorycodedev\MetaapiCloudPhpSdk\Http;

class Configuration
{
    private string $configUrl = 'users/current/configuration';

    public function __construct(private readonly Http $http)
    {
    }

    /*
    *   Generates a new strategy id
    */
    public function generateStrategyId(): array|string|null
    {
        return $this->http->get("/{$this->configUrl}/unused-strategy-id");
    }

    /*
    *   Returns provider strategies the user has configured
    */
    public function strategies(bool $includeRemoved = false, int $limit = 1000, int $offset = 0): array|string|null
    {
        return $this->http->get("/{$this->configUrl}/strategies", [
            'includeRemoved' => $includeRemoved,
            'limit'          => $limit,
            'offset'         => $offset,
        ]);
    }

    public function strategiesV2(bool $includeRemoved = false, int $limit = 1000, int $offset = 0): array|string|null
    {
        return $this->http->get(
            "/{$this->configUrl}/strategies",
            [
                'includeRemoved' => $includeRemoved,
                'limit'          => $limit,
                'offset'         => $offset,
            ],
            ['api-version' => '2']
        );
    }

    /*
    *   Returns provider strategy the user has configured by id
    */
    public function strategy(string $strategyId): array|string|null
    {
        return $this->http->get("/{$this->configUrl}/strategies/{$strategyId}");
    }

    /*
    *   Updates provider strategy
    */
    public function updateStrategy(string $strategyId, array $data): array|string|null
    {
        return $this->http->put("/{$this->configUrl}/strategies/{$strategyId}", $data);
    }

    /*
    *   Deletes provider strategy
    */
    public function removeStrategy(string $strategyId): array|string|null
    {
        return $this->http->delete("/{$this->configUrl}/strategies/{$strategyId}");
    }

    public function portfolioStrategies(bool $includeRemoved = false, int $limit = 1000, int $offset = 0, ?int $apiVersion = null): array|string|null
    {
        return $this->http->get(
            "/{$this->configUrl}/portfolio-strategies",
            [
                'includeRemoved' => $includeRemoved,
                'limit'          => $limit,
                'offset'         => $offset,
            ],
            $apiVersion ? ['api-version' => (string) $apiVersion] : []
        );
    }

    public function portfolioStrategy(string $portfolioId): array|string|null
    {
        return $this->http->get("/{$this->configUrl}/portfolio-strategies/{$portfolioId}");
    }

    public function updatePortfolioStrategy(string $portfolioId, array $data): array|string|null
    {
        return $this->http->put("/{$this->configUrl}/portfolio-strategies/{$portfolioId}", $data);
    }

    public function removePortfolioStrategy(string $portfolioId, array $closeInstructions = []): array|string|null
    {
        return $this->http->delete("/{$this->configUrl}/portfolio-strategies/{$portfolioId}", payload: $closeInstructions);
    }

    public function removePortfolioStrategyMember(string $portfolioId, string $strategyId, array $closeInstructions = []): array|string|null
    {
        return $this->http->delete(
            "/{$this->configUrl}/portfolio-strategies/{$portfolioId}/members/{$strategyId}",
            payload: $closeInstructions
        );
    }

    /*
    *   Returns strategy subscribers the current user provides strategies to
    */
    public function subscribers(bool $includeRemoved = false, int $limit = 1000, int $offset = 0): array|string|null
    {
        return $this->http->get("/{$this->configUrl}/subscribers", [
            'includeRemoved' => $includeRemoved,
            'limit'          => $limit,
            'offset'         => $offset,
        ]);
    }

    public function subscribersV2(bool $includeRemoved = false, int $limit = 1000, int $offset = 0): array|string|null
    {
        return $this->http->get(
            "/{$this->configUrl}/subscribers",
            [
                'includeRemoved' => $includeRemoved,
                'limit'          => $limit,
                'offset'         => $offset,
            ],
            ['api-version' => '2']
        );
    }

    /*
    *   Returns CopyFactory subscriber by id
    */
    public function subscriber(string $subscriberId): array|string|null
    {
        return $this->http->get("/{$this->configUrl}/subscribers/{$subscriberId}");
    }

    /*
    *   Updates subscriber configuration
    */

    public function updateSubscriber(string $subscriberId, array $data): array|string|null
    {
        return $this->http->put("/{$this->configUrl}/subscribers/{$subscriberId}", $data);
    }

    /*
    *   Deletes subscriber configuration
    */
    public function removeSubscriber(string $subscriberId): array|string|null
    {
        return $this->http->delete("/{$this->configUrl}/subscribers/{$subscriberId}");
    }

    /*
    *   Deletes subscription
    */

    public function deleteSubscription(string $subscriberId, string $strategyId): array|string|null
    {
        return $this->http->delete("/{$this->configUrl}/subscribers/{$subscriberId}/subscriptions/{$strategyId}");
    }
}
