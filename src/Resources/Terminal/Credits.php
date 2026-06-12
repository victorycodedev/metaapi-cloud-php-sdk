<?php

namespace Victorycodedev\MetaapiCloudPhpSdk\Resources\Terminal;

use Victorycodedev\MetaapiCloudPhpSdk\Http;

class Credits
{
    public function __construct(private readonly Http $http)
    {
    }

    public function usage(string $accountId): array|string|null
    {
        return $this->http->get("/users/current/accounts/{$accountId}/credits");
    }
}
