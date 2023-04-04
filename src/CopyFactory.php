<?php

namespace Victorycodedev\MetaapiCloudPhpSdk;

use Victorycodedev\MetaapiCloudPhpSdk\Resources\Copyfactory\Configuration;
use Victorycodedev\MetaapiCloudPhpSdk\Resources\Copyfactory\CopyTrade;

class CopyFactory
{
    use Configuration;
    use CopyTrade;

    public Http $http;

    public string $serverUrl = 'https://copyfactory-api-v1.new-york.agiliumtrade.ai';

    public function __construct(private string $token, string $serverUrl = null)
    {
        $this->token = $token;
        $this->serverUrl = $serverUrl ?? $this->serverUrl;
        $this->http = new Http($this->token, $this->serverUrl);
    }
}
