<?php

namespace Victorycodedev\MetaapiCloudPhpSdk\Resources\Metastats;

trait Metrics
{
    /*
    *   Calculates and returns a MetaApi account metrics. This API call is billable
    */
    public function metrics(string $accountId, bool $includeOpenPositions = false): array|string
    {
        return $this->http->get("/users/current/accounts/{$accountId}/metrics?includeOpenPositions={$includeOpenPositions}");
    }

    /*
    *   Returns open trades for MetaApi account. This API call is not billable
    */
    public function openTrades(string $accountId): array|string
    {
        return $this->http->get("/users/current/accounts/{$accountId}/open-trades");
    }
}
