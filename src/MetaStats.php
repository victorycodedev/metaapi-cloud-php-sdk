<?php

namespace Victorycodedev\MetaapiCloudPhpSdk;

class MetaStats
{
    public Http $http;

    public string $baseUrl = 'https://metastats-api-v1.new-york.agiliumtrade.ai';

    public function __construct(private string $token, string $baseUrl = '')
    {
        $this->token = $token;
        $this->baseUrl = $baseUrl === '' ? $this->baseUrl : $baseUrl;
        $this->http = new Http($this->token);
    }
}
