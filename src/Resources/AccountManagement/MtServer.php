<?php

namespace Victorycodedev\MetaapiCloudPhpSdk\Resources\AccountManagement;

use Victorycodedev\MetaapiCloudPhpSdk\Http;

class MtServer
{
    public function __construct(private readonly Http $http) {}

    public function knownTradingServers(int $version, string $query): array|string|null
    {
        return $this->http->get("/known-mt-servers/{$version}/search", ['query' => $query]);
    }
}
