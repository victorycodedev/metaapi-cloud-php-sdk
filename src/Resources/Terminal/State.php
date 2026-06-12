<?php

namespace Victorycodedev\MetaapiCloudPhpSdk\Resources\Terminal;

use Victorycodedev\MetaapiCloudPhpSdk\Http;

class State
{
    public function __construct(private readonly Http $http) {}

    public function accountInformation(string $accountId, bool $refreshTerminalState = false): array|string|null
    {
        return $this->http->get("/users/current/accounts/{$accountId}/account-information", [
            'refreshTerminalState' => $refreshTerminalState,
        ]);
    }

    public function positions(string $accountId, bool $refreshTerminalState = false): array|string|null
    {
        return $this->http->get("/users/current/accounts/{$accountId}/positions", [
            'refreshTerminalState' => $refreshTerminalState,
        ]);
    }

    public function position(string $accountId, string $positionId, bool $refreshTerminalState = false): array|string|null
    {
        return $this->http->get("/users/current/accounts/{$accountId}/positions/" . Path::segment($positionId), [
            'refreshTerminalState' => $refreshTerminalState,
        ]);
    }

    public function orders(string $accountId, bool $refreshTerminalState = false): array|string|null
    {
        return $this->http->get("/users/current/accounts/{$accountId}/orders", [
            'refreshTerminalState' => $refreshTerminalState,
        ]);
    }

    public function order(string $accountId, string $orderId, bool $refreshTerminalState = false): array|string|null
    {
        return $this->http->get("/users/current/accounts/{$accountId}/orders/" . Path::segment($orderId), [
            'refreshTerminalState' => $refreshTerminalState,
        ]);
    }

    public function serverTime(string $accountId): array|string|null
    {
        return $this->http->get("/users/current/accounts/{$accountId}/server-time");
    }
}
