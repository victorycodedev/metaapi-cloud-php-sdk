<?php

namespace Victorycodedev\MetaapiCloudPhpSdk\Resources\Terminal;

use Victorycodedev\MetaapiCloudPhpSdk\Http;

class History
{
    public function __construct(private readonly Http $http)
    {
    }

    public function historyOrdersByTicket(string $accountId, string $ticket): array|string|null
    {
        return $this->http->get("/users/current/accounts/{$accountId}/history-orders/ticket/" . Path::segment($ticket));
    }

    public function historyOrdersByPosition(string $accountId, string $positionId): array|string|null
    {
        return $this->http->get("/users/current/accounts/{$accountId}/history-orders/position/" . Path::segment($positionId));
    }

    public function historyOrdersByTimeRange(string $accountId, string $startTime, string $endTime, int $offset = 0, int $limit = 1000): array|string|null
    {
        return $this->http->get(
            "/users/current/accounts/{$accountId}/history-orders/time/" . Path::segment($startTime) . '/' . Path::segment($endTime),
            ['offset' => $offset, 'limit' => $limit]
        );
    }

    public function dealsByTicket(string $accountId, string $ticket): array|string|null
    {
        return $this->http->get("/users/current/accounts/{$accountId}/history-deals/ticket/" . Path::segment($ticket));
    }

    public function dealsByPosition(string $accountId, string $positionId): array|string|null
    {
        return $this->http->get("/users/current/accounts/{$accountId}/history-deals/position/" . Path::segment($positionId));
    }

    public function dealsByTimeRange(string $accountId, string $startTime, string $endTime, int $offset = 0, int $limit = 1000): array|string|null
    {
        return $this->http->get(
            "/users/current/accounts/{$accountId}/history-deals/time/" . Path::segment($startTime) . '/' . Path::segment($endTime),
            ['offset' => $offset, 'limit' => $limit]
        );
    }
}
