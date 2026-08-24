<?php

namespace Victorycodedev\MetaapiCloudPhpSdk;

use GuzzleHttp\ClientInterface;
use Victorycodedev\MetaapiCloudPhpSdk\Resources\Manager\MetaTrader4Manager;
use Victorycodedev\MetaapiCloudPhpSdk\Resources\Manager\MetaTrader5Manager;

class ManagerApi
{
    private Http $http;

    private MetaTrader4Manager $mt4;

    private MetaTrader5Manager $mt5;

    public function __construct(
        private readonly string $token,
        private readonly string $serverUrl,
        ?ClientInterface $client = null
    ) {
        $this->http = new Http($this->token, $this->serverUrl, $client);
        $this->mt4 = new MetaTrader4Manager($this->http);
        $this->mt5 = new MetaTrader5Manager($this->http);
    }

    public function mt4(): MetaTrader4Manager
    {
        return $this->mt4;
    }

    public function mt5(): MetaTrader5Manager
    {
        return $this->mt5;
    }
}
