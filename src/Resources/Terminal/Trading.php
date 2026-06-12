<?php

namespace Victorycodedev\MetaapiCloudPhpSdk\Resources\Terminal;

use Victorycodedev\MetaapiCloudPhpSdk\Http;

class Trading
{
    public function __construct(private readonly Http $http) {}

    public function trade(string $accountId, array $trade): array|string|null
    {
        return $this->http->post("/users/current/accounts/{$accountId}/trade", $trade);
    }

    public function createMarketBuyOrder(string $accountId, string $symbol, float $volume, ?float $stopLoss = null, ?float $takeProfit = null, array $options = []): array|string|null
    {
        return $this->trade($accountId, array_merge([
            'actionType' => 'ORDER_TYPE_BUY',
            'symbol' => $symbol,
            'volume' => $volume,
            'stopLoss' => $stopLoss,
            'takeProfit' => $takeProfit,
        ], $options));
    }

    public function createMarketSellOrder(string $accountId, string $symbol, float $volume, ?float $stopLoss = null, ?float $takeProfit = null, array $options = []): array|string|null
    {
        return $this->trade($accountId, array_merge([
            'actionType' => 'ORDER_TYPE_SELL',
            'symbol' => $symbol,
            'volume' => $volume,
            'stopLoss' => $stopLoss,
            'takeProfit' => $takeProfit,
        ], $options));
    }

    public function createLimitBuyOrder(string $accountId, string $symbol, float $volume, float $openPrice, ?float $stopLoss = null, ?float $takeProfit = null, array $options = []): array|string|null
    {
        return $this->pendingOrder($accountId, 'ORDER_TYPE_BUY_LIMIT', $symbol, $volume, $openPrice, $stopLoss, $takeProfit, $options);
    }

    public function createLimitSellOrder(string $accountId, string $symbol, float $volume, float $openPrice, ?float $stopLoss = null, ?float $takeProfit = null, array $options = []): array|string|null
    {
        return $this->pendingOrder($accountId, 'ORDER_TYPE_SELL_LIMIT', $symbol, $volume, $openPrice, $stopLoss, $takeProfit, $options);
    }

    public function createStopBuyOrder(string $accountId, string $symbol, float $volume, float $openPrice, ?float $stopLoss = null, ?float $takeProfit = null, array $options = []): array|string|null
    {
        return $this->pendingOrder($accountId, 'ORDER_TYPE_BUY_STOP', $symbol, $volume, $openPrice, $stopLoss, $takeProfit, $options);
    }

    public function createStopSellOrder(string $accountId, string $symbol, float $volume, float $openPrice, ?float $stopLoss = null, ?float $takeProfit = null, array $options = []): array|string|null
    {
        return $this->pendingOrder($accountId, 'ORDER_TYPE_SELL_STOP', $symbol, $volume, $openPrice, $stopLoss, $takeProfit, $options);
    }

    public function createStopLimitBuyOrder(string $accountId, string $symbol, float $volume, float $openPrice, float $stopLimitPrice, ?float $stopLoss = null, ?float $takeProfit = null, array $options = []): array|string|null
    {
        return $this->pendingOrder($accountId, 'ORDER_TYPE_BUY_STOP_LIMIT', $symbol, $volume, $openPrice, $stopLoss, $takeProfit, array_merge([
            'stopLimitPrice' => $stopLimitPrice,
        ], $options));
    }

    public function createStopLimitSellOrder(string $accountId, string $symbol, float $volume, float $openPrice, float $stopLimitPrice, ?float $stopLoss = null, ?float $takeProfit = null, array $options = []): array|string|null
    {
        return $this->pendingOrder($accountId, 'ORDER_TYPE_SELL_STOP_LIMIT', $symbol, $volume, $openPrice, $stopLoss, $takeProfit, array_merge([
            'stopLimitPrice' => $stopLimitPrice,
        ], $options));
    }

    public function modifyPosition(string $accountId, string $positionId, ?float $stopLoss = null, ?float $takeProfit = null, array $options = []): array|string|null
    {
        return $this->trade($accountId, array_merge([
            'actionType' => 'POSITION_MODIFY',
            'positionId' => $positionId,
            'stopLoss' => $stopLoss,
            'takeProfit' => $takeProfit,
        ], $options));
    }

    public function closePosition(string $accountId, string $positionId, array $options = []): array|string|null
    {
        return $this->trade($accountId, array_merge([
            'actionType' => 'POSITION_CLOSE_ID',
            'positionId' => $positionId,
        ], $options));
    }

    public function closePositionPartially(string $accountId, string $positionId, float $volume, array $options = []): array|string|null
    {
        return $this->trade($accountId, array_merge([
            'actionType' => 'POSITION_PARTIAL',
            'positionId' => $positionId,
            'volume' => $volume,
        ], $options));
    }

    public function closePositionsBySymbol(string $accountId, string $symbol, array $options = []): array|string|null
    {
        return $this->trade($accountId, array_merge([
            'actionType' => 'POSITIONS_CLOSE_SYMBOL',
            'symbol' => $symbol,
        ], $options));
    }

    public function closeBy(string $accountId, string $positionId, string $closeByPositionId, array $options = []): array|string|null
    {
        return $this->trade($accountId, array_merge([
            'actionType' => 'POSITION_CLOSE_BY',
            'positionId' => $positionId,
            'closeByPositionId' => $closeByPositionId,
        ], $options));
    }

    public function modifyOrder(string $accountId, string $orderId, float $openPrice, ?float $stopLoss = null, ?float $takeProfit = null, array $options = []): array|string|null
    {
        return $this->trade($accountId, array_merge([
            'actionType' => 'ORDER_MODIFY',
            'orderId' => $orderId,
            'openPrice' => $openPrice,
            'stopLoss' => $stopLoss,
            'takeProfit' => $takeProfit,
        ], $options));
    }

    public function cancelOrder(string $accountId, string $orderId): array|string|null
    {
        return $this->trade($accountId, [
            'actionType' => 'ORDER_CANCEL',
            'orderId' => $orderId,
        ]);
    }

    private function pendingOrder(
        string $accountId,
        string $actionType,
        string $symbol,
        float $volume,
        float $openPrice,
        ?float $stopLoss,
        ?float $takeProfit,
        array $options
    ): array|string|null {
        return $this->trade($accountId, array_merge([
            'actionType' => $actionType,
            'symbol' => $symbol,
            'volume' => $volume,
            'openPrice' => $openPrice,
            'stopLoss' => $stopLoss,
            'takeProfit' => $takeProfit,
        ], $options));
    }
}
