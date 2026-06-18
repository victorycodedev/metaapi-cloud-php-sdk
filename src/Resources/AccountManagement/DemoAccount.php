<?php

namespace Victorycodedev\MetaapiCloudPhpSdk\Resources\AccountManagement;

use Victorycodedev\MetaapiCloudPhpSdk\Http;
use Victorycodedev\MetaapiCloudPhpSdk\Responses\ActionResponse;

class DemoAccount
{
    public function __construct(private readonly Http $http) {}

    public function createMT4DemoAccount(string $profileId, array $data, string $transactionId): ActionResponse
    {
        return $this->http->postAction(
            "/users/current/provisioning-profiles/{$profileId}/mt4-demo-accounts",
            $data,
            ['transaction-id' => $transactionId]
        );
    }

    public function createMT5DemoAccount(string $profileId, array $data, string $transactionId): ActionResponse
    {
        return $this->http->postAction(
            "/users/current/provisioning-profiles/{$profileId}/mt5-demo-accounts",
            $data,
            ['transaction-id' => $transactionId]
        );
    }
}
