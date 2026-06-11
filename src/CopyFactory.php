<?php

namespace Victorycodedev\MetaapiCloudPhpSdk;

use GuzzleHttp\ClientInterface;
use Victorycodedev\MetaapiCloudPhpSdk\Resources\Copyfactory\Configuration;
use Victorycodedev\MetaapiCloudPhpSdk\Resources\Copyfactory\CopyTrade;

class CopyFactory
{
    public Http $http;

    public string $serverUrl = 'https://copyfactory-api-v1.new-york.agiliumtrade.ai';

    private Configuration $configuration;

    private CopyTrade $copyTrade;

    public function __construct(private string $token, ?string $serverUrl = null, ?ClientInterface $client = null)
    {
        $this->serverUrl = $serverUrl ?? $this->serverUrl;
        $this->http = new Http($this->token, $this->serverUrl, $client);
        $this->configuration = new Configuration($this->http);
        $this->copyTrade = new CopyTrade($this->http, $this->configuration, $this->token);
    }

    public function configuration(): Configuration
    {
        return $this->configuration;
    }

    public function copyTrade(): CopyTrade
    {
        return $this->copyTrade;
    }

    public function generateStrategyId(): array|string|null
    {
        return $this->configuration->generateStrategyId();
    }

    public function strategies(bool $includeRemoved = false, int $limit = 1000, int $offset = 0): array|string|null
    {
        return $this->configuration->strategies($includeRemoved, $limit, $offset);
    }

    public function strategy(string $strategyId): array|string|null
    {
        return $this->configuration->strategy($strategyId);
    }

    public function updateStrategy(string $strategyId, array $data): array|string|null
    {
        return $this->configuration->updateStrategy($strategyId, $data);
    }

    public function removeStrategy(string $strategyId): array|string|null
    {
        return $this->configuration->removeStrategy($strategyId);
    }

    public function subscribers(bool $includeRemoved = false, int $limit = 1000, int $offset = 0): array|string|null
    {
        return $this->configuration->subscribers($includeRemoved, $limit, $offset);
    }

    public function subscriber(string $subscriberId): array|string|null
    {
        return $this->configuration->subscriber($subscriberId);
    }

    public function updateSubscriber(string $subscriberId, array $data): array|string|null
    {
        return $this->configuration->updateSubscriber($subscriberId, $data);
    }

    public function removeSubscriber(string $subscriberId): array|string|null
    {
        return $this->configuration->removeSubscriber($subscriberId);
    }

    public function deleteSubscription(string $subscriberId, string $strategyId): array|string|null
    {
        return $this->configuration->deleteSubscription($subscriberId, $strategyId);
    }

    public function copy(string $providerAccount, string $subscriberAccount, ?string $strategyId = null): array|string
    {
        return $this->copyTrade->copy($providerAccount, $subscriberAccount, $strategyId);
    }
}
