<?php

namespace Victorycodedev\MetaapiCloudPhpSdk\Resources\Terminal;

use Victorycodedev\MetaapiCloudPhpSdk\Http;

class MarketData
{
    public function __construct(private readonly Http $http, private readonly Http $marketDataHttp)
    {
    }

    public function symbols(string $accountId): array|string|null
    {
        return $this->http->get("/users/current/accounts/{$accountId}/symbols");
    }

    public function symbolSpecification(string $accountId, string $symbol): array|string|null
    {
        return $this->http->get("/users/current/accounts/{$accountId}/symbols/" . Path::segment($symbol) . '/specification');
    }

    public function symbolPrice(string $accountId, string $symbol, bool $keepSubscription = false): array|string|null
    {
        return $this->http->get("/users/current/accounts/{$accountId}/symbols/" . Path::segment($symbol) . '/current-price', [
            'keepSubscription' => $keepSubscription,
        ]);
    }

    public function candle(string $accountId, string $symbol, string $timeframe, bool $keepSubscription = false): array|string|null
    {
        return $this->http->get(
            "/users/current/accounts/{$accountId}/symbols/" . Path::segment($symbol) . '/current-candles/' . Path::segment($timeframe),
            ['keepSubscription' => $keepSubscription]
        );
    }

    public function tick(string $accountId, string $symbol, bool $keepSubscription = false): array|string|null
    {
        return $this->http->get("/users/current/accounts/{$accountId}/symbols/" . Path::segment($symbol) . '/current-tick', [
            'keepSubscription' => $keepSubscription,
        ]);
    }

    public function orderBook(string $accountId, string $symbol, bool $keepSubscription = false): array|string|null
    {
        return $this->http->get("/users/current/accounts/{$accountId}/symbols/" . Path::segment($symbol) . '/current-book', [
            'keepSubscription' => $keepSubscription,
        ]);
    }

    public function historicalCandles(string $accountId, string $symbol, string $timeframe, ?string $startTime = null, int $limit = 1000): array|string|null
    {
        return $this->marketDataHttp->get(
            "/users/current/accounts/{$accountId}/historical-market-data/symbols/" . Path::segment($symbol) . '/timeframes/' . Path::segment($timeframe) . '/candles',
            ['startTime' => $startTime, 'limit' => $limit]
        );
    }

    public function historicalTicks(string $accountId, string $symbol, ?string $startTime = null, int $offset = 0, int $limit = 1000): array|string|null
    {
        return $this->marketDataHttp->get(
            "/users/current/accounts/{$accountId}/historical-market-data/symbols/" . Path::segment($symbol) . '/ticks',
            ['startTime' => $startTime, 'offset' => $offset, 'limit' => $limit]
        );
    }
}
