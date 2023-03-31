<?php

namespace Victorycodedev\MetaapiCloudPhpSdk;

class AccountApi
{
    public $token;

    public Http $http;
    public string $baseUrl = 'https://mt-provisioning-api-v1.agiliumtrade.agiliumtrade.ai';

    public function __construct(string $token, string $baseUrl = '')
    {
        $this->token = $token;
        $this->baseUrl = $baseUrl === '' ? $this->baseUrl : $baseUrl;
        $this->http = new Http();
    }

    public function readAccountById(string $accountId): array|string
    {
        return $this->http->get("{$this->baseUrl}/users/current/accounts/{$accountId}", $this->token);
    }
}
