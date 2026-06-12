<?php

namespace Victorycodedev\MetaapiCloudPhpSdk\Resources\Metastats;

use Victorycodedev\MetaapiCloudPhpSdk\Http;

class Metrics
{
    public function __construct(private readonly Http $http) {}

    /*
    *   Calculates and returns a MetaApi account metrics. This API call is billable
    */
    public function metrics(string $accountId, bool $includeOpenPositions = false): array|string|null
    {
        return $this->http->get("/users/current/accounts/{$accountId}/metrics", [
            'includeOpenPositions' => $includeOpenPositions,
        ]);
    }

    public function historicalTrades(string $accountId, string $startTime, string $endTime, array $query = []): array|string|null
    {
        $startTime = rawurlencode($startTime);
        $endTime = rawurlencode($endTime);

        return $this->http->get("/users/current/accounts/{$accountId}/historical-trades/{$startTime}/{$endTime}", $query);
    }

    /*
    *   Returns open trades for MetaApi account. This API call is not billable
    */
    public function openTrades(string $accountId): array|string|null
    {
        return $this->http->get("/users/current/accounts/{$accountId}/open-trades");
    }

    public function resetMetrics(string $accountId): array|string|null
    {
        return $this->http->delete("/users/current/accounts/{$accountId}");
    }
}
