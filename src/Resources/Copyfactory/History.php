<?php

namespace Victorycodedev\MetaapiCloudPhpSdk\Resources\Copyfactory;

use Victorycodedev\MetaapiCloudPhpSdk\Http;

class History
{
    public function __construct(private readonly Http $http)
    {
    }

    public function providedTransactions(array $query): array|string|null
    {
        return $this->http->get('/users/current/provided-transactions', $query);
    }

    public function subscriptionTransactions(array $query): array|string|null
    {
        return $this->http->get('/users/current/subscription-transactions', $query);
    }

    public function strategyTransactionsStream(string $strategyId, array $query = []): array|string|null
    {
        return $this->http->get("/users/current/strategies/{$strategyId}/transactions/stream", $query);
    }

    public function subscriberTransactionsStream(string $subscriberId, array $query = []): array|string|null
    {
        return $this->http->get("/users/current/subscribers/{$subscriberId}/transactions/stream", $query);
    }
}
