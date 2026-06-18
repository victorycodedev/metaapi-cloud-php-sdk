<?php

namespace Victorycodedev\MetaapiCloudPhpSdk\Resources\AccountManagement;

use Victorycodedev\MetaapiCloudPhpSdk\Http;

class Quota
{
    public function __construct(private readonly Http $http) {}

    public function quotas(): array|string|null
    {
        return $this->http->get('/users/current/quotas');
    }

    public function quotaUpdateRequests(): array|string|null
    {
        return $this->http->get('/users/current/quota-update-requests');
    }

    public function requestRegionQuotaUpdate(string $region, array $data): array|string|null
    {
        return $this->http->patch("/users/current/regions/{$region}/quotas", $data);
    }
}
