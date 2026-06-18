<?php

namespace Victorycodedev\MetaapiCloudPhpSdk\Resources\AccountManagement;

use Victorycodedev\MetaapiCloudPhpSdk\Http;
use Victorycodedev\MetaapiCloudPhpSdk\Responses\ActionResponse;

class DemoAccount
{
    public function __construct(private readonly Http $http) {}

    public function createMT4DemoAccount(string $profileId, array $data, ?string $transactionId = null): ActionResponse
    {
        return $this->http->postAction(
            "/users/current/provisioning-profiles/{$profileId}/mt4-demo-accounts",
            $data,
            $this->transactionHeader($transactionId)
        );
    }

    public function createMT5DemoAccount(string $profileId, array $data, ?string $transactionId = null): ActionResponse
    {
        return $this->http->postAction(
            "/users/current/provisioning-profiles/{$profileId}/mt5-demo-accounts",
            $data,
            $this->transactionHeader($transactionId)
        );
    }

    private function transactionHeader(?string $transactionId): array
    {
        return ['transaction-id' => $transactionId ?? bin2hex(random_bytes(16))];
    }
}
