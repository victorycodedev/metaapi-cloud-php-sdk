<?php

namespace Victorycodedev\MetaapiCloudPhpSdk\Resources\Terminal;

use Victorycodedev\MetaapiCloudPhpSdk\Http;

class Margin
{
    public function __construct(private readonly Http $http)
    {
    }

    public function calculate(string $accountId, array $order): array|string|null
    {
        return $this->http->post("/users/current/accounts/{$accountId}/calculate-margin", $order);
    }
}
