<?php

namespace Victorycodedev\MetaapiCloudPhpSdk\Resources\AccountManagement;

use Victorycodedev\MetaapiCloudPhpSdk\Traits\FetchApi;

class Account
{
    use FetchApi;

    protected string $baseUrl = 'https://mt-provisioning-api-v1.agiliumtrade.agiliumtrade.ai';

    public function readAccountById(string $accountId)
    {
        return $this->get("{$this->baseUrl}/users/current/accounts/{$accountId}");
    }
}
