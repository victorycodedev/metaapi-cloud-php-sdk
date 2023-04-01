<?php

namespace Victorycodedev\MetaapiCloudPhpSdk;

use Victorycodedev\MetaapiCloudPhpSdk\Resources\AccountManagement\Account;

class AccountApi
{
    use Account;

    public Http $http;

    public string $baseUrl = 'https://mt-provisioning-api-v1.agiliumtrade.agiliumtrade.ai';

    public function __construct(private string $token, string $baseUrl = '')
    {
        $this->token = $token;
        $this->baseUrl = $baseUrl === '' ? $this->baseUrl : $baseUrl;
        $this->http = new Http($this->token);
    }
}
