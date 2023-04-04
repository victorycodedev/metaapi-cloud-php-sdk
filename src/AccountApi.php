<?php

namespace Victorycodedev\MetaapiCloudPhpSdk;

use Victorycodedev\MetaapiCloudPhpSdk\Resources\AccountManagement\Account;

class AccountApi
{
    use Account;

    public Http $http;

    public string $serverUrl = 'https://mt-provisioning-api-v1.agiliumtrade.agiliumtrade.ai';

    public function __construct(private string $token, string $serverUrl = null)
    {
        $this->token = $token;
        $this->serverUrl = $serverUrl ?? $this->serverUrl;
        $this->http = new Http($this->token, $this->serverUrl);
    }
}
