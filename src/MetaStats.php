<?php

namespace Victorycodedev\MetaapiCloudPhpSdk;

use GuzzleHttp\ClientInterface;
use Victorycodedev\MetaapiCloudPhpSdk\Resources\Metastats\Metrics;

class MetaStats
{
    public Http $http;

    public string $serverUrl = 'https://metastats-api-v1.new-york.agiliumtrade.ai';

    private Metrics $metrics;

    public function __construct(private string $token, ?string $serverUrl = null, ?ClientInterface $client = null)
    {
        $this->serverUrl = $serverUrl ?? $this->serverUrl;
        $this->http = new Http($this->token, $this->serverUrl, $client);
        $this->metrics = new Metrics($this->http);
    }

    public function metricResource(): Metrics
    {
        return $this->metrics;
    }

    public function metrics(string $accountId, bool $includeOpenPositions = false): array|string|null
    {
        return $this->metrics->metrics($accountId, $includeOpenPositions);
    }

    public function historicalTrades(string $accountId, string $startTime, string $endTime, array $query = []): array|string|null
    {
        return $this->metrics->historicalTrades($accountId, $startTime, $endTime, $query);
    }

    public function openTrades(string $accountId): array|string|null
    {
        return $this->metrics->openTrades($accountId);
    }

    public function resetMetrics(string $accountId): array|string|null
    {
        return $this->metrics->resetMetrics($accountId);
    }
}
